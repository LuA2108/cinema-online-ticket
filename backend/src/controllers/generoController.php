<?php

namespace App\Controllers;

use App\Models\Genero;
use App\Service\GeneroService;

/**
 * Clase GeneroController
 * Coordina las operaciones del módulo de géneros
 * Utiliza el servicio GeneroService para realizar operaciones CRUD
 */
class GeneroController
{

    private GeneroService $generoService;

    /**
     * Constructor de la clase
     * @param GeneroService $generoService servicio de géneros para realizar operaciones CRUD
     */
    public function __construct($generoService)
    {
        $this->generoService = $generoService;
    }

    /**
     * Obtener todos los géneros
     * @return array Resultado de la operación con éxito o error
     */
    public function index()
    {
        return $this->generoService->listarGeneros();
    }

    /**
     * Obtener género por ID
     * @param int $id
     * @return array Resultado de la operación con éxito o error
    */
    public function mostrarGenero($id)
    {
        return $this->generoService->obtenerGenero($id);
    }

    /**
     * Crear género
     * @param string $nombre
     * @return array Resultado de la operación con éxito o error
     */
    public function crearGenero($nombre)
    {
        return $this->generoService->crearGenero($nombre);
    }

    /**
     * Actualizar género
     * @param int $id ID del género a actualizar
     * @param string $nombre Nuevo nombre del género
     * @return array Resultado de la operación con éxito o error
     */
    public function actualizarGenero($id, $nombre)
    {
        return $this->generoService->actualizarGenero($id, $nombre);
    }

    /**
     * Eliminar género
     * @param int $id ID del género a eliminar
     * @return array Resultado de la operación con éxito o error
     */
    public function eliminarGenero($id)
    {
        return $this->generoService->eliminarGenero($id);
    }
}
