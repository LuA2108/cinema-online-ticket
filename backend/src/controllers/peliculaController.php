<?php

namespace App\Controller;

use App\service\PeliculaService;

class PeliculaController
{
    private $peliculaService;

    /**
     * Constructor de la clase controlador película
     * @param mysqli $conn Conexion a la BD
     */
    public function __construct($conn)
    {
        $this->peliculaService = new PeliculaService($conn);
    }

    /**
     * Obtiene una lista de todas las películas de la base de datos y la devuelve en formato JSON
     * @return void
     */
    public function index()
    {
        echo json_encode($this->peliculaService->listarPeliculas());
    }

    public function listarPeliculasCompletas()
    {
        echo json_encode($this->peliculaService->listarPeliculasCompletas());
    }

    /**
     * Obtiene una película por su ID y la devuelve en formato JSON
     * @param int $id
     * @return void
     */
    public function mostrarPelicula($id)
    {
        echo json_encode($this->peliculaService->obtenerPeliculaPorId($id));
    }
    /**
     * Guarda una nueva película en la base de datos utilizando los datos proporcionados en el cuerpo de la solicitud HTTP
     * @return void
     */
    public function guardarPelicula()
    {
        $datos = $_POST;
        $pelicula = $datos['pelicula'];
        $generos = $datos['generos'];

        $resultado = $this->peliculaService->crearPelicula($pelicula, $generos);
        echo json_encode($resultado);
    }

    public function actualizarPelicula()
    {
        $datos = $_POST;
        $pelicula = $datos['pelicula'];
        $generos = $datos['generos'];

        $resultado = $this->peliculaService->actualizarPelicula($pelicula['id'], $pelicula, $generos);
        echo json_encode($resultado);
    }
    /**
     * Desactiva una película de la base de datos utilizando su ID
     * @param int $id
     * @return void
     */
    public function activar($id)
    {
        echo json_encode($this->peliculaService->activarPelicula($id));
    }

    /**
     * Summary of desactivar
     * @param mixed $id
     * @return void
     */
    public function desactivar($id)
    {
        echo json_encode($this->peliculaService->desactivarPelicula($id));
    }
}
