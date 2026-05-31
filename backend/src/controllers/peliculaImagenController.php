<?php

namespace App\Controllers;

use App\Service\PeliculaImagenService;

/**
 * Clase PeliculaImagenController
 * Controlador para manejar las solicitudes relacionadas con las imágenes de películas, utilizando el servicio PeliculaImagenService para la lógica de negocio
 * Inyección de dependencias para el servicio necesario en el controlador
 */
class PeliculaImagenController
{

    private PeliculaImagenService $peliculaImagenService;

    /**
     * Constructor de la clase PeliculaImagenController
     * @param PeliculaImagenService $peliculaImagenService Servicio para manejar la lógica de negocio relacionada con las imágenes de películas
     * Inyección de dependencias para el servicio necesario en el controlador
     */
    public function __construct($peliculaImagenService)
    {
        $this->peliculaImagenService = $peliculaImagenService;
    }

    /**
     * Lista las imagenes de todas las películas
     * @return array
     */
    public function index()
    {
        return $this->peliculaImagenService->listarTodas();
    }

    /**
     * Lista las imagenes de una película específica utilizando su ID
     * @param int $peliculaId ID de la película para la cual se desean listar las imágenes
     * @return array Resultado de la operación de listado de imágenes por película, incluyendo un array de imágenes o un mensaje de error si la operación falla 
     */
    public function imagenesPorPelicula(int $peliculaId)
    {
        return $this->peliculaImagenService->listarImagenesPorPelicula($peliculaId);
    }

    /**
     * Obtiene una imagen específica por su ID
     * @param int $imagenId
     * @return array Resultado de la operación de obtención de imagen, incluyendo los detalles de la imagen o un mensaje de error si la operación falla
     */
    public function obtenerImagen(int $imagenId)
    {
        return $this->peliculaImagenService->obtenerImagen($imagenId);
    }

    /**
     * Crea una nueva imagen para una película utilizando los datos proporcionados
     * @param array $datos
     * @param mixed $file Archivo de imagen
     * @return array Resultado de la operación de creación de imagen
     */
    public function crearImagen(array $datos, $file = null)
    {
        return $this->peliculaImagenService->agregar(
            (int)$datos['pelicula_id'],
            $datos['tipo'],
            $file
        );
    }

    /**
     * Elimina una imagen de la base de datos utilizando su ID
     * @param int $imagenId
     * @return array Resultado de la operación de eliminación, incluyendo un mensaje de éxito o error según corresponda
     */
    public function eliminarImagen(int $imagenId)
    {
        return $this->peliculaImagenService->eliminar($imagenId);
    }
}
