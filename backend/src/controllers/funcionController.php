<?php

namespace App\Controllers;

use App\Service\FuncionService;

class FuncionController
{
    private FuncionService $funcionService;

    /**
     * Constructor de la clase FuncionController
     */
    public function __construct(FuncionService $funcionService)
    {
        $this->funcionService = $funcionService;
    }

    /**
     * Lista todas las funciones
     */
    public function listarFunciones(): array
    {
        return $this->funcionService->listarFunciones();
    }

    /**
     * Obtiene una función por ID
     */
    public function obtenerFuncionId(int $id): array
    {
        return $this->funcionService->obtenerFuncionId($id);
    }

    /**
     * Obtiene funciones por estado
     */
    public function obtenerFuncionesPorEstado(int $estadoId): array
    {
        return $this->funcionService->obtenerFuncionPorEstado($estadoId);
    }

}
