<?php

namespace App\Controllers;

use App\Service\FuncionService;

class FuncionController
{
    private FuncionService $funcionService;

    /**
     * Constructor de la clase
     * Inyección del servicio de funciones.
     * @param FuncionService $funcionService
     */
    public function __construct($funcionService)
    {
        $this->funcionService = $funcionService;
    }

    /**
     * Devuelve todas las funciones.
     * No recibe parámetros.
     */
    public function listarFunciones()
    {
        return $this->funcionService->listarFunciones();
    }

    /**
     * Obtiene una función por ID.
     * @param int $id Identificador de la función (puede venir como string desde JSON)
     */
    public function obtenerFuncionId($id)
    {
        return $this->funcionService->obtenerFuncionId($id);
    }

    /**
     * Crea una nueva función.
     * @param array $datos Datos enviados desde el cliente (JSON normalmente)
     */
    public function crearFuncion($datos)
    {
        return $this->funcionService->crearFuncion($datos);
    }

    /**
     * Actualiza una función existente.
     * @param int $funcion_id ID de la función a actualizar
     * @param array $datos Datos nuevos de funcion
     * @return array
     */
    public function actualizarFuncion($funcion_id, $datos)
    {
        return $this->funcionService->actualizarFuncion($funcion_id, $datos);
    }

    /**
     * Elimina una función por ID.
     * @param int $funcion_id
     */
    public function eliminarFuncion($funcion_id)
    {
        return $this->funcionService->eliminarFuncion($funcion_id);
    }

    /**
     * Obtiene funciones filtradas por estado.
     * @param int $estado_id
     * @return array
     */
    public function obtenerFuncionesPorEstado($estado_id)
    {
        return $this->funcionService->obtenerFuncionPorEstado($estado_id);
    }

    /**
     * Obtiene funciones filtradas por película.
     * @param int $pelicula_id
     * @return array
     */
    public function obtenerFuncionesPorPelicula($pelicula_id)
    {
        return $this->funcionService->obtenerFuncionesPorPelicula($pelicula_id);
    }

    /**
     * Obtiene todos los estados disponibles.
     * @return array
     */
    public function obtenerEstados()
    {
        return $this->funcionService->obtenerEstados();
    }

    /**
     * Obtiene un estado específico por ID.
     * @param int $estado_id
     * @return array
     */
    public function obtenerEstado($estado_id)
    {
        return $this->funcionService->obtenerEstado($estado_id);
    }
}
