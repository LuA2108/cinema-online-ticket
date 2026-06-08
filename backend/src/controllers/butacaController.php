<?php

namespace App\Controllers;
use App\service\butacaService;

/**
 * Controlador ButacaController
 * Gestiona las peticiones relacionadas con butacas.
 * Solo conecta routes con el service.
 */
class ButacaController
{
    private butacaService $butacaService;

    /**
     * Constructor
     * @param ButacaService $butacaService
     */
    public function __construct(ButacaService $butacaService)
    {
        $this->butacaService = $butacaService;
    }

    /**
     * Obtiene una butaca según su ID
     * @param int $id ID de la butaca
     * @return array
     */
    public function obtenerButacaPorId($id)
    {
        // Convierte el ID a entero y llama al service
        return $this->butacaService->obtenerButacaPorId((int)$id);
    }

    /**
     * Obtiene todas las butacas de una sala
     * @param int $salaId ID de la sala
     * @return array
     */
    public function obtenerButacasPorSala($salaId)
    {
        // Convierte el ID a entero y llama al service
        return $this->butacaService->obtenerButacasPorSala((int)$salaId);
    }

    /**
     * Obtiene el mapa de butacas de una función
     * mostrando libres y ocupadas
     * @param int $funcionId ID de la función
     * @return array
     */
    public function obtenerMapaButacas($funcionId)
    {
        // Convierte el ID a entero y llama al service
        return $this->butacaService->obtenerMapaButacas((int)$funcionId);
    }
}