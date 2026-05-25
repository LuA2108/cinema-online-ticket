<?php

namespace App\Controllers;

use App\Service\PeliculaService;

/**
 * Controlador de pelicula
 * 
 */
class PeliculaController
{
    private PeliculaService $peliculaService;

    /**
     * Constructor de la clase controlador película
     * @param PeliculaService $peliculaService El servicio de película que se utilizará para manejar la lógica de negocio relacionada con las películas
     */
    public function __construct(PeliculaService $peliculaService)
    {
        $this->peliculaService = $peliculaService;
    }

    /**
     * Obtiene una lista de todas las películas de la base de datos
     * @return array Un array de películas
     */
    public function index()
    {
        return $this->peliculaService->listarPeliculas();
    }

    /**
     * Lista todas las películas con detalles completos de la base de datos incluyendo géneros e imágenes asociadas
     * @return array Un array de películas con detalles completos
     */
    public function listarPeliculasCompletas()
    {
        return $this->peliculaService->listarPeliculasCompletas();
    }

    /**
     * Obtiene los detalles de una película específica utilizando su ID
     * @param int $id ID de la película que se desea obtener
     * @return array Un array con los detalles de la película, incluyendo sus géneros e imágenes asociadas
     */
    public function mostrarPelicula($id)
    {
        return $this->peliculaService->obtenerPeliculaPorId($id);
    }
    /**
     * Guarda una nueva película en la base de datos utilizando los datos proporcionados 
     * @return array Resultado de la operación de guardado, incluyendo el ID de la nueva película o un mensaje de error si la operación falla
     */
    public function guardarPelicula(array $datos)
    {
        $pelicula = $datos['pelicula'];
        $generos = $datos['generos'];

        return $this->peliculaService->crearPelicula($pelicula, $generos);
    }

    /**
     * Actualiza los datos de una película existente en la base de datos utilizando su ID y los nuevos datos proporcionados
     * @param int $id ID de la película que se desea actualizar
     * @param array $datos Los datos de la película a actualizar, incluyendo el ID de la película, los nuevos datos de la película y los géneros asociados
     * @return array Resultado de la operación de actualización, incluyendo un mensaje de éxito o error según corresponda
    */ 
    public function actualizarPelicula($id, $datos)
    {
        $pelicula = $datos['pelicula'];
        $generos = $datos['generos'];

        $resultado = $this->peliculaService->actualizarPelicula($id, $pelicula, $generos);
        return $resultado;
    }

    /**
     * Activa una película de la base de datos utilizando su ID
     * @param int $id
     * @return array Resultado de la operación de activación, incluyendo un mensaje de éxito o error según corresponda
     */
    public function activar($id)
    {
        return $this->peliculaService->activarPelicula($id);
    }

    /**
     * Desactiva una película de la base de datos utilizando su ID
     * @param mixed $id
     * @return array Resultado de la operación de desactivación, incluyendo un mensaje de éxito o error según corresponda
     */
    public function desactivar($id)
    {
        return $this->peliculaService->desactivarPelicula($id);
    }
}
