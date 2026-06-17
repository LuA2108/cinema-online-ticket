<?php

namespace App\Controllers;

use App\Service\ReservaButacaService;

class ReservaButacaController
{
    private ReservaButacaService $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * POST /reserva-butacas
     * Agregar butaca a reserva
     */
    public function agregarButacaAReserva($data)
    {
        return $this->service->agregarButacaAReserva(
            $data['reserva_id'] ?? null,
            $data['butaca_id'] ?? null,
        );
    }

    /**
     * DELETE /reserva-butacas
     * Eliminar butaca de reserva
     */
    public function eliminarButacaDeReserva($data)
    {
        return $this->service->eliminarButacaDeReserva(
            $data['reserva_id'] ?? null,
            $data['butaca_id'] ?? null
        );
    }

    /**
     * GET /reserva-butacas/reserva/{id}
     */
    public function obtenerPorReserva($reserva_id)
    {
        return $this->service->obtenerButacasPorReserva((int)$reserva_id);
    }

    /**
     * GET /reserva-butacas/funcion/{id}
     */
    public function obtenerPorFuncion($funcion_id)
    {
        return $this->service->obtenerButacasPorFuncion((int)$funcion_id);
    }
}