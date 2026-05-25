<?php

namespace App\Service;

use App\Models\Producto;
use App\Models\TipoProducto;

/**
 * Clase ProductoService
 * Gestiona la lógica de negocio de productos.
 * Aplica validaciones y devuelve respuestas estructuradas.
 */
class ProductoService
{
    /**
     * Modelo Producto
     * @var Producto
     */
    private Producto $productoModel;

    /**
     * Modelo TipoProducto
     * Se usa para validar que el tipo exista
     * @var TipoProducto
     */
    private TipoProducto $tipoProductoModel;

    /**
     * Constructor
     * @param Producto $productoModel
     * @param TipoProducto $tipoProductoModel
     */
    public function __construct(Producto $productoModel, TipoProducto $tipoProductoModel) 
    {
        $this->productoModel = $productoModel;
        $this->tipoProductoModel = $tipoProductoModel;
    }

    /**
     * Obtiene todos los productos
     * @return array
     */
    public function listarProductos(): array
    {
        return ["success" => true, "datos" => $this->productoModel->listarProductos(), "error" => null];
    }

    /**
     * Obtiene un producto por ID
     * @param int $id ID del producto
     * @return array
     */
    public function obtenerProductoPorId(int $id): array
    {
        // Validar ID
        if ($id <= 0) {
            return ["success" => false, "datos" => null,"error" => "ID inválido"];
        }

        // Buscar producto
        $producto = $this->productoModel->obtenerProductoPorId($id);

        // Validar existencia
        if (!$producto) {
            return ["success" => false,"datos" => null, "error" => "Producto no encontrado"];
        }

        return ["success" => true, "datos" => $producto, "error" => null];
    }

    /**
     * Obtiene productos filtrados por tipo
     * @param int $tipoId ID del tipo de producto
     * @return array
     */
    public function listarProductosPorTipo(int $tipoId): array
    {
        // Validar existencia del tipo
        $tipo = $this->tipoProductoModel->obtenerPorId($tipoId);

        if (!$tipo) {
            return ["success" => false, "datos" => null, "error" => "El tipo de producto no existe"];
        }

        return ["success" => true, "datos" => $this->productoModel->listarProductosPorTipo($tipoId), "error" => null];
    }

    /**
     * Busca productos por nombre
     * @param string $nombre Nombre o parte del nombre
     * @return array
     */
    public function obtenerProductoPorNombre(string $nombre): array
    {
        // Validar texto vacío
        if (trim($nombre) === '') {
            return ["success" => false, "datos" => null, "error" => "El nombre no puede estar vacío"];
        }

        return ["success" => true, "datos" => $this->productoModel->obtenerProductoPorNombre($nombre), "error" => null];
    }

    /**
     * Agrega un nuevo producto
     * @param array $datos Datos del producto
     * @return array
     */
    public function agregarProducto(array $datos): array
    {
        // Campos obligatorios
        $campos = ['nombre', 'precio', 'tipo_id', 'comentario'];

        // Validar campos
        foreach ($campos as $campo) {

            if (!isset($datos[$campo]) || $datos[$campo] === '') {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        // Validar precio
        if (!is_numeric($datos['precio']) || $datos['precio'] < 0) {
            return ["success" => false, "datos" => null, "error" => "Precio inválido"];
        }

        // Validar existencia de tipo
        $tipo = $this->tipoProductoModel->obtenerPorId($datos['tipo_id']);

        if (!$tipo) {
            return ["success" => false, "datos" => null, "error" => "El tipo de producto no existe"];
        }

        // Crear producto
        $resultado = $this->productoModel->agregarProducto($datos['nombre'], $datos['precio'], $datos['tipo_id'], $datos['comentario']);

        // Validar resultado
        if (!$resultado) {
            return ["success" => false, "datos" => null, "error" => "Error al crear el producto"];
        }

        return ["success" => true, "datos" => true, "error" => null];
    }

    /**
     * Actualiza un producto existente
     * @param int $id ID del producto
     * @param array $datos Nuevos datos
     * @return array
     */
    public function actualizarProducto(int $id, array $datos): array
    {
        // Validar existencia del producto
        $producto = $this->productoModel->obtenerProductoPorId($id);

        if (!$producto) {
            return ["success" => false, "datos" => null, "error" => "El producto no existe"];
        }

        // Validar existencia del tipo
        $tipo = $this->tipoProductoModel->obtenerPorId($datos['tipo_id']);

        if (!$tipo) {
            return ["success" => false, "datos" => null, "error" => "El tipo de producto no existe"];
        }

        // Actualizar producto
        $resultado = $this->productoModel->actualizarProducto($id, $datos['nombre'], $datos['precio'], $datos['tipo_id'], $datos['comentario']);

        return $resultado
            ? ["success" => true, "datos" => true, "error" => null]
            : ["success" => false, "datos" => null, "error" => "Error al actualizar el producto"];
    }

    /**
     * Elimina un producto
     * @param int $id ID del producto
     * @return array
     */
    public function eliminarProducto(int $id): array
    {
        // Validar existencia
        $producto = $this->productoModel->obtenerProductoPorId($id);

        if (!$producto) {
            return ["success" => false, "datos" => null, "error" => "El producto no existe"];
        }

        // Eliminar producto
        $resultado = $this->productoModel->eliminarProducto($id);

        return $resultado
            ? ["success" => true, "datos" => true, "error" => null]
            : ["success" => false, "datos" => null, "error" => "Error al eliminar el producto"];
    }
}
