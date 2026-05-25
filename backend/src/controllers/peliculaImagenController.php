<?php

namespace App\Controller;

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

    public function index()
    {
        return $this->peliculaImagenService->listarTodas();
    }

    public function imagenesPorPelicula(int $peliculaId)
    {
        return $this->peliculaImagenService->listarImagenesPorPelicula($peliculaId);
    }

    public function obtenerImagen(int $imagenId)
    {
        return $this->peliculaImagenService->obtenerImagen($imagenId);
    }

    /**
     * Summary of crearImagen
     * @param array $datos
     * @throws \InvalidArgumentException
     */
    public function crearImagen(array $datos)
    {
        return $this->peliculaImagenService->agregar(
            (int)$datos['pelicula_id'],
            $datos['tipo'],
            $datos['url']
        );
    }

    public function eliminarImagen(int $imagenId)
    {
        return $this->peliculaImagenService->eliminar($imagenId);
    }
}
