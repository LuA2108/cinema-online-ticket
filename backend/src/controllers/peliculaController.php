<?php

namespace App\Controller;

use App\service\PeliculaService;

class PeliculaController
{
    private PeliculaService $peliculaService;

    /**
     * Constructor de la clase controlador película
     */
    public function __construct($peliculaService)
    {
        $this->peliculaService = $peliculaService;
    }

    /**
     * Obtiene una lista de todas las películas de la base de datos y la devuelve en formato JSON
     */
    public function index()
    {
        return $this->peliculaService->listarPeliculas();
    }

    public function listarPeliculasCompletas()
    {
        return $this->peliculaService->listarPeliculasCompletas();
    }

    /**
     * Obtiene una película por su ID y la devuelve en formato JSON
     * @param int $id
     */
    public function mostrarPelicula($id)
    {
        return $this->peliculaService->obtenerPeliculaPorId($id);
    }
    /**
     * Guarda una nueva película en la base de datos utilizando los datos proporcionados en el cuerpo de la solicitud HTTP
     */
    public function guardarPelicula()
    {
        $datos = $_POST;
        $pelicula = $datos['pelicula'];
        $generos = $datos['generos'];

        $resultado = $this->peliculaService->crearPelicula($pelicula, $generos);
        return $resultado;
    }

    public function actualizarPelicula()
    {
        $datos = $_POST;
        $pelicula = $datos['pelicula'];
        $generos = $datos['generos'];

        $resultado = $this->peliculaService->actualizarPelicula($pelicula['id'], $pelicula, $generos);
        return $resultado;
    }
    /**
     * Desactiva una película de la base de datos utilizando su ID
     * @param int $id
     */
    public function activar($id)
    {
        return $this->peliculaService->activarPelicula($id);
    }

    /**
     * Summary of desactivar
     * @param mixed $id
     */
    public function desactivar($id)
    {
        return $this->peliculaService->desactivarPelicula($id);
    }
}
