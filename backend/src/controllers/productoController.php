<?php

namespace App\Controllers;

use App\Service\ProductoService;

/**
 * Controlador ProductoController
 * Gestiona las peticiones relacionadas con productos.
 * Solo conecta routes con el service.
 */
class ProductoController
{
    /**
     * Servicio de productos
     * @var ProductoService
     */
    private ProductoService $service;

    /**
     * Constructor
     * @param ProductoService $service
     */
    public function __construct(ProductoService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtiene todos los productos
     * @return array
     */
    public function listarProductos()
    {
        // Llama al service para obtener todos los productos
        return $this->service->listarProductos();
    }

    /**
     * Obtiene un producto por ID
     * @param int $id ID del producto
     * @return array
     */
    public function obtenerProductoPorId($id)
    {
        // Convierte el ID a entero y llama al service
        return $this->service->obtenerProductoPorId((int)$id);
    }

    /**
     * Obtiene productos filtrados por tipo
     * @param int $tipoId ID del tipo de producto
     * @return array
     */
    public function listarProductosPorTipo($tipoId)
    {
        // Convierte el ID a entero y llama al service
        return $this->service->listarProductosPorTipo((int)$tipoId);
    }

    /**
     * Busca productos por nombre
     * @param string $nombre Nombre o parte del nombre
     * @return array
     */
    public function obtenerProductoPorNombre($nombre)
    {
        // Envía el texto al service
        return $this->service->obtenerProductoPorNombre($nombre);
    }

    /**
     * Agrega un nuevo producto
     * @param array $data Datos del producto
     * @return array
     */
    public function agregarProducto($data)
    {
        // Envía los datos al service
        return $this->service->agregarProducto($data);
    }

    /**
     * Actualiza un producto existente
     * @param int $id ID del producto
     * @param array $data Nuevos datos
     * @return array
     */
    public function actualizarProducto($id, $data)
    {
        // Convierte el ID a entero y envía datos al service
        return $this->service->actualizarProducto((int)$id, $data);
    }

    /**
     * Elimina un producto
     * @param int $id ID del producto
     * @return array
     */
    public function eliminarProducto($id)
    {
        // Convierte el ID a entero y llama al service
        return $this->service->eliminarProducto((int)$id);
    }
}
