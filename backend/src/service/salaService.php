<?php

namespace App\Service;

use App\Models\Sala;
use App\Models\Butaca;
use Exception;

/**
 * Clase servicio de Sala
 * Interactúa con el modelo de Sala para realizar operaciones CRUD, excepto delete (desactiva en vez de eliminar)
 * Realiza validaciones y formatea las respuestas para el controlador
 * Utiliza inyección de dependencias para el modelo de Sala 
 */
class SalaService
{
    private Sala $salaModel;
    private Butaca $butacaModel;
    private $conn;

    /**
     * Constructor de la clase SalaService
     * @param Sala $salaModel
     * @param Butaca $butacaModel
     * @param mysqli $conn
     */
    public function __construct($salaModel, $butacaModel, $conn)
    {
        $this->conn = $conn;
        $this->salaModel = $salaModel;
        $this->butacaModel = $butacaModel;
    }

    /**
     * Listar todas las salas 
     * @return array
     */
    public function listarSalas()
    {

        return ["success" => true, "datos" => $this->salaModel->listarSalas(), "error" => null];
    }

    /**
     * Listar una sala por su ID
     * @param mixed $sala_id
     * @return array
     */
    public function obtenerSala($sala_id)
    {
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "Sala no encontrada"];
        }

        return ["success" => true, "datos" => $sala, "error" => null];
    }

    /**
     * Listar todas las salas por estado
     * @param boolean $estado
     * @return array
     */
    public function listarPorEstado($estado)
    {
        return ["success" => true, "datos" => $this->salaModel->listarSalaPorEstado($estado), "error" => null];
    }

    /**
     * Crea una nueva sala con el número y capacidad proporcionados
     * @param int $filas
     * @param int $butacasPorFila
     * @return array
     */
    public function crearSala($filas, $butacasPorFila)
    {
        if ($filas <= 0 || $butacasPorFila <= 0) {
            return ["success" => false, "datos" => null, "error" => "Datos invalidos."];
        }

        try {
            $this->conn->begin_transaction();
            $salaId = $this->salaModel->agregarSala($filas, $butacasPorFila, false);

            if ($salaId < 0) {
                throw new Exception("No se pudo crear la sala");
            }

            for ($fila = 1; $fila <= $filas; $fila++) {
                for ($numeroButaca = 1; $numeroButaca <=  $butacasPorFila; $numeroButaca++) {
                    $resultado = $this->butacaModel->insertarButaca($salaId, $fila, $numeroButaca);

                    if ($resultado < 0) {
                        throw new Exception("Error creando butaca");
                    }
                }
            }

            $this->conn->commit();
            return ["success" => true, "datos" => $salaId, "error" => null];
        } catch (Exception $e) {
            $this->conn->rollback();
            return ["success" => false, "datos" => null, "error" => $e->getMessage()];
        }
    }

    /**
     * Desactiva una sala existente
     * @param int $sala_id ID de la sala a desactivar
     * @param boolean $estado
     * @return array
     */
    public function cambiarEstadoSala($sala_id, $estado)
    {
        if ($sala_id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID inválido"];
        }

        // Obtener sala y comprobar que existe
        $sala = $this->salaModel->listarSala($sala_id);

        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "Sala no encontrada"];
        }

        $estado = (int) $estado;
        $ok = $this->salaModel->cambiarEstadoSala($sala_id, $estado);

        return [
            "success" => $ok,
            "datos" => $ok,
            "error" => $ok ? null : "No se pudo desactivar la sala"
        ];
    }
}
