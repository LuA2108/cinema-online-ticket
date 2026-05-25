<?php

namespace App\Service;

use App\Models\TipoProducto;

/**
 * Clase TipoProductoService
 * Se encarga de la lógica de negocio de los tipos de producto.
 * Valida datos y estructura las respuestas.
 */
class TipoProductoService
{
    private TipoProducto $tipoProductoModel;

    /**
     * Constructor
     * @param TipoProducto $tipoProductoModel
     */
    public function __construct(TipoProducto $tipoProductoModel)
    {
        $this->tipoProductoModel = $tipoProductoModel;
    }

    /**
     * Listar todos los tipos de producto
     */
    public function listar(): array
    {
        return ["success" => true, "datos" => $this->tipoProductoModel->listar(), "error" => null];
    }

    /**
     * Obtener tipo de producto por ID
     */
    public function obtenerPorId(int $id): array
    {
        if ($id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID inválido"];
        }

        $tipo = $this->tipoProductoModel->obtenerPorId($id);

        if (!$tipo) {
            return [ "success" => false, "datos" => null, "error" => "Tipo de producto no encontrado"];
        }

        return ["success" => true, "datos" => $tipo, "error" => null];
    }

    /**
     * Crear tipo de producto
     */
    public function crear(array $datos): array
    {
        // Validación de campos obligatorios
        $campos = ['nombre', 'descripcion'];

        foreach ($campos as $campo) {
            if (!isset($datos[$campo]) || trim($datos[$campo]) === '') {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        $resultado = $this->tipoProductoModel->crear($datos['nombre'], $datos['descripcion']);

        if (!$resultado) {
            return ["success" => false, "datos" => null, "error" => "Error al crear el tipo de producto"];
        }

        return ["success" => true, "datos" => true, "error" => null];
    }

    /**
     * Actualizar tipo de producto
     */
    public function actualizar(int $id, array $datos): array
    {
        $tipo = $this->tipoProductoModel->obtenerPorId($id);

        if (!$tipo) {
            return ["success" => false, "datos" => null, "error" => "El tipo de producto no existe"];
        }

        // Validación básica
        if (!isset($datos['nombre']) || !isset($datos['descripcion'])) {
            return ["success" => false, "datos" => null, "error" => "Faltan datos obligatorios"];
        }

        $resultado = $this->tipoProductoModel->actualizar($id, $datos['nombre'], $datos['descripcion']);

        return $resultado
            ? ["success" => true, "datos" => true, "error" => null]
            : ["success" => false, "datos" => null, "error" => "Error al actualizar"];
    }

    /**
     * Eliminar tipo de producto
     */
    public function eliminar(int $id): array
    {
        $tipo = $this->tipoProductoModel->obtenerPorId($id);

        if (!$tipo) {
            return ["success" => false,"datos" => null,"error" => "El tipo de producto no existe"];
        }

        $resultado = $this->tipoProductoModel->eliminar($id);

        return $resultado
            ? ["success" => true, "datos" => true, "error" => null]
            : ["success" => false, "datos" => null, "error" => "Error al eliminar"];
    }

    /**
     * Buscar por nombre
     */
    public function buscarPorNombre(string $nombre): array
    {
        if (trim($nombre) === '') {
            return ["success" => false, "datos" => null, "error" => "El nombre de búsqueda no puede estar vacío"];
        }
        $resultados = $this->tipoProductoModel->buscarPorNombre($nombre);

        return ["success" => true,"datos" => $resultados,"error" => null];
    }
}