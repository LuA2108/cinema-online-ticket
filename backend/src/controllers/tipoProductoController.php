<?php

namespace App\Controllers;

use App\Service\TipoProductoService;

/**
 * Controlador de TipoProducto
 * Se encarga únicamente de recibir las peticiones desde routes
 * y delegarlas al Service correspondiente.
 * No contiene lógica de negocio ni acceso a base de datos.
 */
class TipoProductoController
{
    private TipoProductoService $service;

    /**
     * Constructor del controlador
     * Se inyecta el servicio para poder usarlo en los métodos del controlador
     * @param TipoProductoService $service
     */
    public function __construct(TipoProductoService $service)
    {
        $this->service = $service;
    }

    /**
     * Muestra el listado de todos los tipos de producto
     * @return array Respuesta estructurada del servicio
     */
    public function index()
    {
        $respuesta = $this->service->listar();
        return $respuesta;
    }

    /**
     * Obtiene un tipo de producto por su ID
     * @param int $id Identificador del tipo de producto
     * @return array Respuesta estructurada del service
     */
    public function obtenerTipoProductoID($id)
    {
        $respuesta = $this->service->obtenerPorId((int)$id);
        return $respuesta;
    }

    /**
     * Guarda un nuevo tipo de producto
     * @param array $data Datos del tipo de producto (nombre, descripción, etc.)
     * @return array Respuesta del service (éxito o error)
     */
    public function guardarTipoProducto($data)
    {
        $respuesta = $this->service->crear($data);
        return $respuesta;
    }

    /**
     * Actualiza un tipo de producto existente
     * @param int $id ID del tipo de producto a actualizar
     * @param array $data Nuevos datos del tipo de producto
     * @return array Resultado de la operación
     */
    public function actualizarTipoProducto($id, $data)
    {
        $respuesta = $this->service->actualizar((int)$id, $data);
        return $respuesta;
    }

    /**
     * Elimina un tipo de producto por su ID
     * @param int $id ID del tipo de producto a eliminar
     * @return array Resultado de la operación
     */
    public function eliminarTipoProducto($id)
    {
        $respuesta = $this->service->eliminar((int)$id);
        return $respuesta;
    }

    /**
     * Busca tipos de producto por nombre (coincidencia parcial)
     * @param string $nombre Texto a buscar
     * @return array Resultados encontrados
     */
    public function buscarPorNombreTipoProducto($nombre)
    {
        $respuesta = $this->service->buscarPorNombre($nombre);
        return $respuesta;
    }
}