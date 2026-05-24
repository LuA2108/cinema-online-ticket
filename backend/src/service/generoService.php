<?php

namespace App\Service;

use App\Models\Genero;

/**
 * Clase GeneroService
 * Gestiona la lógica de negocio relacionada con los géneros de películas
 */
class GeneroService
{
    private Genero $generoModel;

    /**
     * Constructor de la clase
     * @param Genero $generoModel modelo de género para realizar operaciones CRUD
     */
    public function __construct($generoModel)
    {
        $this->generoModel = $generoModel;
    }

    public function listarGeneros()
    {
        return $this->generoModel->obtenerTodos();
    }

    public function obtenerGenero($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException("El ID del género debe ser un número positivo.");
        }

        return $this->generoModel->obtenerPorId($id);
    }

    public function crearGenero($nombre)
    {
        $nombre = trim($nombre);

        if (empty($nombre)) {
            throw new \InvalidArgumentException("El nombre del género no puede estar vacío.");
        }

        $id = $this->generoModel->crearGenero($nombre);

        if ($id === -1) {
            throw new \Exception("Error al crear el género.");
        }

        return $id;
    }

    public function actualizarGenero($id, $nombre)
    {
        $nombre = trim($nombre);

        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException("El ID del género debe ser un número positivo.");
        }

        if (empty($nombre)) {
            throw new \InvalidArgumentException("El nombre del género no puede estar vacío.");
        }

        return $this->generoModel->actualizarGenero($id, $nombre);
    }

    public function eliminarGenero($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException("El ID del género debe ser un número positivo.");
        }
        return $this->generoModel->eliminarGenero($id);
    }
}
