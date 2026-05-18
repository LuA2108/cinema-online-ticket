<?php

/**
 * Clase que gestiona las operaciones CRUD de películas
 */
class Pelicula
{
    private $conn;

    /**
     * Constructor de la clase película
     * @param mysqli $conn Conexión a la BD
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Función que devuelve una lista de todas las películas
     */
    public function listarPeliculas()
    {
        $sql = "SELECT * FROM pelicula";
        $resultado = $this->conn->query($sql);

        $peliculas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $peliculas[] = $fila;
        }

        return $peliculas;
    }

    public function listarPeliculasCompletas()
    {
        $sql = " SELECT p.id, p.titulo, p.descripcion, pi.url AS poster, GROUP_CONCAT(g.nombre SEPARATOR ', ') AS generos,
        p.director, p.anio, p.duracion, p.precio, p.disponible, p.create_time AS fecha_registro
        FROM pelicula p
        LEFT JOIN pelicula_imagen pi ON p.id = pi.pelicula_id AND pi.tipo = 'poster'
        LEFT JOIN pelicula_genero pg ON p.id = pg.pelicula_id
        LEFT JOIN genero g ON pg.genero_id = g.id
        GROUP BY p.id";

        $resultado = $this->conn->query($sql);
        $peliculas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $peliculas[] = $fila;
        }
        return $peliculas;
    }

    /**
     * Función que busca una película por ID
     * @param int $id ID de la película
     */
    public function peliculaId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM pelicula WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }
}
