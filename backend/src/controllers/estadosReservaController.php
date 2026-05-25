<?php

namespace App\Controllers;

use App\Service\EstadoReservaService;

/**
 * Controller EstadoReserva
 * Solo conecta routes con service
 */
class EstadoReservaController
{
    private EstadoReservaService $service;

    public function __construct(EstadoReservaService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar estados
     */
    public function index()
    {
        return $this->service->listar();
    }

    /**
     * Obtener estado por ID
     */
    public function mostrarEstadoPorID($id)
    {
        return $this->service->obtenerPorId((int)$id);
    }
}