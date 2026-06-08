<?php

namespace App\Controllers;

use App\Service\ProgramacionService;

class ProgramacionController
{
    private ProgramacionService $programacionService;

    /**
     * Constructor de la clase ProgramacionController
     * @param ProgramacionService $programacionService
     */
    public function __construct(ProgramacionService $programacionService)
    {
        $this->programacionService = $programacionService;
    }

    /**
     * Lista todas las programaciones
     * @return array
     */
    public function listarProgramaciones(): array
    {
        return $this->programacionService->listarProgramaciones();
    }

    /**
     * Obtiene una programación por ID
     * @param int $programacionId
     * @return array
     */
    public function obtenerProgramacion(int $programacionId): array
    {
        return $this->programacionService->obtenerProgramacion($programacionId);
    }

    /**
     * Crea una nueva programación
     * @param array $datos
     * @return array
     */
    public function crearProgramacion(array $datos): array
    {
        return $this->programacionService->crearProgramacion($datos);
    }

    /**
     * Actualiza una programación existente
     * @param int $programacionId
     * @param array $datos
     * @return array
     */
    public function actualizarProgramacion(int $programacionId, array $datos): array
    {
        return $this->programacionService->actualizarProgramacion($programacionId, $datos);
    }

    /**
     * Elimina una programación
     * @param int $programacionId
     * @return array
     */
    public function eliminarProgramacion(int $programacionId): array
    {
        return $this->programacionService->eliminarProgramacion($programacionId);
    }

    /**
     * Obtiene programaciones por película
     * @param int $peliculaId
     * @return array
     */
    public function obtenerProgramacionesPorPelicula(int $peliculaId): array
    {
        return $this->programacionService->obtenerProgramacionesPorPelicula($peliculaId);
    }

    /**
     * Cambia el estado de una programación
     * @param int $programacionId
     * @param bool $estado
     * @return array
     */
    public function cambiarEstado(int $programacionId, bool $estado): array
    {
        return $this->programacionService->cambiarEstado($programacionId, $estado);
    }
}
