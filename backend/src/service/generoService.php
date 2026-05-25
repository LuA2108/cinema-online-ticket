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
        return ["success" => true, "datos" => $this->generoModel->obtenerTodos(), "error" => null];
    }

    public function obtenerGenero($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return ["success" => false, "datos" => null, "error" => "El ID del género debe ser un número positivo"];
        }

        return ["success" => true, "datos" => $this->generoModel->obtenerPorId($id), "error" => null];
    }

    public function crearGenero($nombre)
    {
        $nombre = trim($nombre);

        if (empty($nombre)) {
            return ["success" => false, "datos" => null, "error" => "El nombre del género no puede estar vacío"];
        }

        $id = $this->generoModel->crearGenero($nombre);

        if ($id === -1) {
            return ["success" => false, "datos" => null, "error" => "Error al crear el género."];
        }

        return ["success" => true, "datos" => $id, "error" => null];
    }

    public function actualizarGenero($id, $nombre)
    {
        $nombre = trim($nombre);

        if (!is_numeric($id) || $id <= 0) {
            return ["success" => false, "datos" => null, "error" => "El ID del género debe ser un número positivo."];
        }

        if (empty($nombre)) {
            return ["success" => false, "datos" => null, "error" => "El nombre del género no puede estar vacío."];
        }

        $genero = $this->generoModel->obtenerPorId($id);
        if (!$genero) {
            return ["success" => false, "datos" => null, "error" => "No se encontró el género con ID: $id."];
        }

        return ["success" => true, "datos" => $this->generoModel->actualizarGenero($id, $nombre), "error" => null];
    }

    public function eliminarGenero($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return ["success" => false, "datos" => null, "error" => "El ID del género debe ser un número positivo."];
        }
        return ["success" => true, "datos" => $this->generoModel->eliminarGenero($id), "error" => null];
    }
}
