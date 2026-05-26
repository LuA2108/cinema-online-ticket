<?php

namespace App\Controllers;

use App\service\ReservaService;

/**
 * Controlador ReservaController
 * Solo actúa como puente entre routes y service
 */
class ReservaController
{
    private ReservaService $reservaService;

    /**
     * Constructor con inyección de dependencias
     * @param ReservaService $reservaService
     */
    public function __construct($reservaService)
    {
        $this->reservaService = $reservaService;
    }

    /**
     * Listar todas las reservas
     */
    public function listarReservas(): array
    {
        return $this->reservaService->listarReservas();
    }

    /**
     * Obtener reserva por ID
     */
    public function obtenerReservaId(int $id): array
    {
        return $this->reservaService->obtenerReservaId($id);
    }

    /**
     * Crear reserva
     */
    public function crearReserva(array $data): array
    {
        return $this->reservaService->crearReserva($data);
    }

    /**
     * Actualizar reserva (estado + total)
     */
    public function actualizarReserva(int $id, array $data): array
    {
        return $this->reservaService->actualizarReserva($id, $data);
    }

    /**
     * Eliminar reserva
     */
    public function eliminarReserva(int $id): array
    {
        return $this->reservaService->eliminarReserva($id);
    }

    /**
     * Cambiar estado de reserva
     */
    public function cambiarEstadoReserva(int $id, int $estado_id): array
    {
        return $this->reservaService->cambiarEstadoReserva($id, $estado_id);
    }

    /**
     * Obtener reservas por usuario
     */
    public function obtenerPorUsuario(int $usuario_id): array
    {
        return $this->reservaService->obtenerPorUsuario($usuario_id);
    }

    /**
     * Obtener reservas por función
     */
    public function obtenerPorFuncion(int $funcion_id): array
    {
        return $this->reservaService->obtenerPorFuncion($funcion_id);
    }
}