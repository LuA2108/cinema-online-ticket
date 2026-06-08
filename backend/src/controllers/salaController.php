<?php

namespace App\Controllers;

use App\Service\SalaService;

class SalaController
{
    private SalaService $salaService;

    /**
     * Constructor de la clase SalaController
     */
    public function __construct(SalaService $salaService)
    {
        $this->salaService = $salaService;
    }

    /**
     * Lista todas las salas
     */
    public function listarSalas(): array
    {
        return $this->salaService->listarSalas();
    }

    /**
     * Obtiene una sala por ID
     */
    public function obtenerSala(int $sala_id): array
    {
        return $this->salaService->obtenerSala($sala_id);
    }

    /**
     * Lista salas según su estado
     */
    public function listarPorEstado(bool $estado): array
    {
        return $this->salaService->listarPorEstado($estado);
    }

    /**
     * Crea una nueva sala
     */
    public function crearSala(int $numero, int $filas, int $butacasPorFila): array
    {
        return $this->salaService->crearSala($numero, $filas, $butacasPorFila);
    }

    /**
     * Cambia el estado de una sala
     */
    public function cambiarEstadoSala(int $sala_id, bool $estado): array
    {
        return $this->salaService->cambiarEstadoSala($sala_id, $estado);
    }
}
