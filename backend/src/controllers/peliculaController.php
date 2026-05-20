<?php
require __DIR__ . "../models/pelicula.php";
require __DIR__ . "../models/genero.php";
require __DIR__ . "../models/pelicula_imagen.php";

class PeliculaController
{
    private $peliculaModel;
    private $imagenModel;
    private $generoModel;

    /**
     * Constructor de la clase controlador película
     * @param mysqli $conn Conexion a la BD
     */
    public function __construct($conn)
    {
        $this->peliculaModel = new Pelicula($conn);
    }

    /**
     * Obtiene una lista completa con todos los datos de una película y sus relaciones (generos e imagen) según el ID de la película
     * @param mixed $id ID de la película
     * @return array Lista con datos de la película
     */
    public function detallePelicula($id)
    {
        // 1. Datos de la película
        $pelicula = $this->peliculaModel->peliculaId($id);

        // 2. Poster / imagen principal
        $poster = $this->imagenModel->obtenerPorTipo($id, 'poster');

        // 3. Géneros
        $generos = $this->generoModel->mostrarGenerosPelicula($id);

        // 4. Unir todo en un solo array
        $pelicula['poster'] = $poster;
        $pelicula['generos'] = $generos;

        return $pelicula;
    }

    public function listarPeliculasCompletas() {
        $peliculas = $this->peliculaModel->listarPeliculasCompletas();
    }

    
}
