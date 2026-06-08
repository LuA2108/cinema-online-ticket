<?php

namespace App\Service;

use App\Models\Butaca;
use App\Models\Sala;
use App\Models\Funcion;
use App\Models\Programacion;

/**
 * Clase ButacaServicio
 * Gestiona la creación de butacas y obtiene las butacas segun la sala y función
 */
class ButacaService
{
    private $conn;
    private Butaca $butacaModel;
    private Sala $salaModel;
    private Funcion $funcionModel;
    private Programacion $programacionModel;

    /**
     * Constructor de la clase
     * @param Butaca $butacaModel instancia del modelo Butaca para acceder a sus métodos
     * @param Sala $salaModel instancia del modelo Sala para acceder a sus métodos
     * @param Funcion $funcionModel instancia del modelo función
     * @param Programacion $programacionModel;
     * @param mysqli $conn Conexion a BD
     */
    public function __construct($butacaModel, $salaModel, $funcionModel, $programacionModel, $conn)
    {
        $this->conn = $conn;
        $this->butacaModel = $butacaModel;
        $this->salaModel = $salaModel;
        $this->funcionModel = $funcionModel;
        $this->programacionModel = $programacionModel;
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
     * @param int $funcionId
     * @return array
     */
    public function obtenerMapaButacas($funcionId)
    {
        // Validar dato $funcion_id
        if ($funcionId <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID de función inválido"];
        }

        $funcion = $this->funcionModel->obtenerPorId($funcionId);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "Función no encontrada"];
        }
        
        if ($funcion['estado_id'] != 1) {
            return [
                "success" => false,
                "datos" => null,
                "error" => "La función no está activa"
            ];
        }

        $programacion = $this->programacionModel->obtenerProgramacion($funcion['programacion_id']);

        if (!$programacion) {
            return ["success" => false, "datos" => null, "error" => "Programación no encontrada"];
        }

        // Obtener el id de sala para obtener la sala
        $sala_id = $programacion['sala_id'];
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "Sala no encontrada"];
        }

        // Obtener butacas de la sala
        $butacas = $this->butacaModel->obtenerPorSala($sala_id);

        // Obtener ocupadas de esa función
        $sql = $this->conn->prepare("SELECT rb.butaca_id FROM reserva_butaca rb INNER JOIN reserva r ON rb.reserva_id = r.id WHERE r.funcion_id = ?");
        $sql->bind_param("i", $funcionId);
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
                "funcion" => [
                    "id" => $funcion['id'],
                    "fecha_hora" => $funcion['fecha_hora'],
                    "estado_id" => $funcion['estado_id']
                ],
                "sala" => [
                    "id" => $sala['id'],
                    "numero" => $sala['numero'],
                    "filas" => $sala['filas'],
                    "butacas_por_fila" => $sala['butacas_por_fila']
                ],
                "mapa_butacas" => $mapa
            ],
            "error" => null
        ];
    }
}
