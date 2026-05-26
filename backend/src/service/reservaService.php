<?php

namespace App\service;

use App\Models\Reserva;
use App\Models\Funcion;
use App\Models\EstadoReserva;
use App\Models\Usuario;

use Exception;

class ReservaService
{

    private Reserva $reservaModel;
    private Funcion $funcionModel;
    private EstadoReserva $estadoReservaModel;
    private Usuario $usuarioModel;
    private $conn;

    /**
     * Constructor de la clase reserva con inyección de dependecias
     * @param Reserva $reservaModel
     * @param Usuario $usuarioModel
     * @param EstadoReserva $estadoReservaModel
     * @param Funcion $funcionModel
     * @param mysqli $conn
     */
    public function __construct($reservaModel, $usuarioModel, $estadoReservaModel, $funcionModel, $conn)
    {
        $this->reservaModel = $reservaModel;
        $this->funcionModel = $funcionModel;
        $this->estadoReservaModel = $estadoReservaModel;
        $this->usuarioModel = $usuarioModel;
    }

    /**
     * Lista todas las reservas
     * @return array{datos: mixed, error: null, success: bool}
     */
    public function listarReservas(): array
    {
        return ["success" => true, "datos" => $this->reservaModel->listarReservas(), "error" => null];
    }

    /**
     * Obtiene los datos de una reserva por el ID
     * @param int $id
     * @return array{datos: mixed, error: null, success: bool|array{datos: null, error: string, success: bool}}
     */
    public function obtenerReservaId(int $id): array
    {
        $reserva = $this->reservaModel->obtenerReservaPorId($id);

        if (!$reserva) {
            return ["success" => false, "datos" => null, "error" => "Reserva no encontrada"];
        }

        return ["success" => true, "datos" => $reserva, "error" => null];
    }

    public function crearReserva($datos): array
    {

        $campos = ['funcion_id', 'estado_id', 'email_cliente'];

        foreach ($campos as $campo) {
            if (!isset($datos[$campo])) {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        // Validar función
        $funcion = $this->funcionModel->obtenerPorId($datos['funcion_id']);
        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "La función no existe"];
        }

        // Validar estado
        $estado = $this->estadoReservaModel->obtenerEstado($datos['estado_id']);
        if (!$estado) {
            return ["success" => false, "datos" => null, "error" => "El estado de reserva no existe"];
        }

        // Usuario opcional - No es necesario que exista usuario, no se comprueba
        $usuario_id = $datos['usuario_id'] ?? null;

        // Total inicial
        $total = $datos['total'] ?? 0;

        $reserva_id = $this->reservaModel->crearReserva($usuario_id, $datos['nombre_cliente'] ?? null, $datos['email_cliente'], $datos['funcion_id'], $datos['estado_id'], $total);

        if ($reserva_id === -1) {
            return ["success" => false, "datos" => null, "error" => "Error al crear la reserva"];
        }

        return [
            "success" => true,
            "datos" => ["id" => $reserva_id],
            "error" => null
        ];
    }

    public function actualizarReserva(int $id, array $datos): array
    {
        $reserva = $this->reservaModel->obtenerReservaPorId($id);

        if (!$reserva) {
            return ["success" => false, "datos" => null, "error" => "La reserva no existe"];
        }

        $estado = $this->reservaModel->actualizarEstado($id, (int)$datos['estado_id']);
        $total = $this->reservaModel->actualizarTotal($id, (float)$datos['total']);

        if (!$estado || !$total) {
            return ["success" => false, "datos" => null, "error" => "Error al actualizar la reserva"];
        }

        return ["success" => true, "datos" => true, "error" => null];
    }

    public function eliminarReserva(int $id): array
    {
        $reserva = $this->reservaModel->obtenerReservaPorId($id);

        if (!$reserva) {
            return ["success" => false, "datos" => null, "error" => "La reserva no existe"];
        }
        $resultado = $this->reservaModel->eliminarReserva($id);

        return $resultado
            ? ["success" => true, "datos" => true, "error" => null]
            : ["success" => false, "datos" => null, "error" => "Error al eliminar"];
    }

    public function cambiarEstadoReserva(int $reservaId, int $estado_id): array
    {
        $reserva = $this->reservaModel->obtenerReservaPorId($reservaId);

        if (!$reserva) {
            return ["success" => false, "datos" => null, "error" => "La reserva no existe"];
        }
        $estado = $this->estadoReservaModel->obtenerEstado($estado_id);

        if (!$estado) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe"];
        }

        $resultado = $this->reservaModel->actualizarEstado($reservaId, $estado_id);

        return $resultado
            ? ["success" => true, "datos" => true, "error" => null]
            : ["success" => false, "datos" => null, "error" => "Error al cambiar estado"];
    }

    /**
     * OBTENER RESERVAS POR USUARIO
     */
    public function obtenerPorUsuario(int $usuario_id): array
    {
        $usuario = $this->usuarioModel->buscarPorID($usuario_id);

        if (!$usuario) {
            return ["success" => false, "datos" => null, "error" => "Usuario no existe"];
        }
        return ["success" => true, "datos" => $this->reservaModel->buscarReservaUsuario($usuario_id), "error" => null];
    }

    /**
     * OBTENER RESERVAS POR FUNCION
     */
    public function obtenerPorFuncion(int $funcion_id): array
    {
        $funcion = $this->funcionModel->obtenerPorId($funcion_id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "Función no existe"];
        }
        return ["success" => true, "datos" => $this->reservaModel->obtenerPorFuncion($funcion_id), "error" => null];
    }

}
