<?php

namespace App\service;

use App\Models\Butaca;
use App\Models\Sala;
use App\Models\Funcion;

use Exception;

/**
 * Clase ButacaServicio
 * Gestiona la creación de butacas y obtiene las butacas segun la sala y función
 */
class butacaService
{
    private $conn;
    private Butaca $butacaModel;
    private Sala $salaModel;
    private Funcion $funcionModel;

    /**
     * Constructor de la clase
     * @param Butaca $butacaModel instancia del modelo Butaca para acceder a sus métodos
     * @param Sala $salaModel instancia del modelo Sala para acceder a sus métodos
     * @param Funcion $funcionModel instancia del modelo función
     * @param mysqli $conn Conexion a BD
     */
    public function __construct($butacaModel, $salaModel, $funcionModel, $conn)
    {
        $this->conn = $conn;
        $this->butacaModel = $butacaModel;
        $this->salaModel = $salaModel;
        $this->funcionModel = $funcionModel;
    }

    /**
     * Obtiene los datos de una butaca según el ID
     * @param int $butacaId 
     */
    public function obtenerButacaPorId($butacaId)
    {
        if ($butacaId <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID inválido"];
        }
        $butaca = $this->butacaModel->obtenerPorId($butacaId);

        if (!$butaca) {
            return ["success" => false, "datos" => null, "error" => "Butaca no encontrada"];
        }
        return ["success" => true, "datos" => $butaca, "error" => null];
    }

    /**
     * Obtiene una lista de butacas segun el ID de la sala
     * @param mixed $sala_id
     * @return array{data: mixed, error: null, success: bool}
     */
    public function obtenerButacasPorSala($sala_id)
    {
        if ($sala_id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID de sala inválido"];
        }
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "Sala no encontrada"];
        }

        return ["success" => true, "datos" => $this->butacaModel->obtenerPorSala($sala_id), "error" => null];
    }

    /**
     * Obtiene el mapa de butacas de una función indicando cuáles están libres u ocupadas
     * @param mixed $funcion_id
     * @return array
     */
    public function obtenerMapaButacas($funcion_id)
    {
        // Validar dato $funcion_id
        if ($funcion_id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID de función inválido"];
        }

        // Obtener función
        $funcion = $this->funcionModel->obtenerPorId($funcion_id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "Función no encontrada"];
        }

        // Obtener funciones activas de la sala
        if ((int)$funcion['estado_id'] !== 1) {
            return [
                "success" => false,
                "datos" => null,
                "error" => "La función no está activa"
            ];
        }

        // Obtener sala
        $sala_id = $funcion['sala_id'];
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "Sala no encontrada"];
        }

        // Obtener butacas de la sala
        $butacas = $this->butacaModel->obtenerPorSala($sala_id);

        // Obtener ocupadas de esa función
        $sql = $this->conn->prepare("SELECT butaca_id FROM reserva_butaca WHERE funcion_id = ?");
        $sql->bind_param("i", $funcion_id);
        $sql->execute();

        $result  = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $ocupadas = array_column($result, 'butaca_id');

        // Construir mapa estructurado por filas
        $mapa = [];

        foreach ($butacas as $b) {
            $fila = $b['fila'];

            if (!isset($mapa[$fila])) {
                $mapa[$fila] = [];
            }

            $mapa[$fila][] = [
                "id" => $b['id'],
                "numero" => $b['numero'],
                "fila" => $b['fila'],
                "estado" => in_array($b['id'], $ocupadas) ? "ocupada" : "libre"
            ];
        }

        return [
            "success" => true,
            "datos" => [
                "funcion" => ["id" => $funcion['id'], "pelicula_id" => $funcion['pelicula_id'], "sala_id" => $sala_id, "hora" => $funcion['hora']],
                "sala" => ["id" => $sala['id'], "numero" => $sala['numero'], "capacidad" => $sala['capacidad']],
                "mapa_butacas" => $mapa
            ],
            "error" => null
        ];
    }

    /**
     * Agrega butacas en una sala (ID) formando una matriz
     * @param int $sala_id ID de la sala donde se generarán las butacas
     * @param int $filas Cantidad de filas de butacas a crear
     * @param int $porFila Cantidad de butacas por cada fila
     * @return array
     */
    public function generarButacas($sala_id, $filas, $porFila)
    {
        // Validar datos básicos
        if ($sala_id <= 0 || $filas <= 0 || $porFila <= 0) {
            return ["success" => false, "datos" => null, "error" => "Datos inválidos"];
        }

        // Verificar que la sala exista
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "La sala no existe"];
        }

        // Verificar que la sala esté activa
        if (!$sala['activa']) {
            return ["success" => false, "datos" => null, "error" => "La sala está desactivada"];
        }

        // Verificar si ya existen butacas
        $butacasExistentes = $this->butacaModel->obtenerPorSala($sala_id);

        if (!empty($butacasExistentes)) {
            return ["success" => false, "datos" => null, "error" => "La sala ya tiene butacas generadas"];
        }

        try {

            // Iniciar transacción
            $this->conn->begin_transaction();

            // Generar matriz de butacas
            for ($fila = 1; $fila <= $filas; $fila++) {

                for ($numero = 1; $numero <= $porFila; $numero++) {
                    $resultado = $this->butacaModel->insertarButaca($sala_id, $numero, $fila);

                    if ($resultado === -1) {
                        $this->conn->rollback();
                        return ["success" => false, "datos" => null, "error" => "Error al insertar butacas"];
                    }
                }
            }

            // Confirmar cambios
            $this->conn->commit();
            return ["success" => true, "datos" => true, "error" => null];
        } catch (Exception $e) {

            // Revertir cambios si falla algo
            $this->conn->rollback();

            return ["success" => false, "datos" => null, "error" => $e->getMessage()];
        }
    }

    /**
     * Elimina todas las butacas de una sala específica
     * @param int $sala_id
     * @return array
     */
    public function eliminarButacasSala($sala_id)
    {
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "Sala no encontrada"];
        }

        $resultado = $this->butacaModel->eliminarPorSala($sala_id);

        if ($resultado) {
            return ["success" => true, "datos" => true, "error" => null];
        } else {
            return ["success" => false, "datos" => null, "error" => "Error al eliminar butacas"];
        }
    }
}
