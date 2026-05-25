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
     */
    public function index()
    {
        return $this->generoService->listarGeneros();
    }

    /**
     * Obtener género por ID
     * @param int $id
      * @return array|null
     */
    public function mostrarGenero($id)
    {
        return $this->generoService->obtenerGenero($id);
    }

    /**
     * Crear género
     * @param string $nombre
     * @return int ID del nuevo género o -1 si hubo un error
     */
    public function crearGenero($nombre)
    {
        if (!isset($nombre)) {
            throw new \InvalidArgumentException(
                "El nombre del género es obligatorio."
            );
        }
        return $this->generoService->crearGenero($nombre);
    }

    /**
     * Actualizar género
     * @param int $id ID del género a actualizar
     * @param string $nombre Nuevo nombre del género
     * @return bool true si se actualizó correctamente, false en caso contrario
     */
    public function actualizarGenero($id, $nombre)
    {
        if (!isset($nombre)) {
            throw new \InvalidArgumentException(
                "El nombre del género es obligatorio."
            );
        }
        return $this->generoService->actualizarGenero($id, $nombre);
    }

    /**
     * Eliminar género
     * @param int $id ID del género a eliminar
     * @return bool true si se eliminó correctamente, false en caso contrario
     */
    public function eliminarGenero($id)
    {
        return $this->generoService->eliminarGenero($id);
    }
}
