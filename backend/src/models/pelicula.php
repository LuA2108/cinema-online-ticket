<?php

namespace App\Models;

/**
 * Clase Película
 * gestiona las operaciones CRUD de películas
 * Usa una conexion mysqli mediante inyección de dependencias
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

    /**
     * Obtiene una lista completa con todos los datos de la pelicula y sus relaciones
     * @return array Arra Lista de películas
     */
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
     * Devuelve una pelicula según el ID
     * @param int $id ID de la película
     */
    public function peliculaId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM pelicula WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Crea una nueva película en la base de datos
     * @param string $titulo_pelicula
     * @param string $descripcion
     * @param string $director
     * @param int $anio
     * @param int $duracion
     * @param float $precio
     * @param boolean $disponible
     * @return bool True si se inserto, false al falla
     */
    public function agregarPelicula($titulo_pelicula, $descripcion, $director, $anio, $duracion, $precio, $disponible)
    {
        $sql = $this->conn->prepare("INSERT INTO pelicula(titulo, descripcion, director, anio, duracion, precio, disponible) VALUES(?,?,?,?,?,?,?)");
        $disponible = (int)$disponible;
        $sql->bind_param("sssiiidi", $titulo_pelicula, $descripcion, $director, $anio, $duracion, $precio, $disponible);

        // Ejecutar
        $resultado = $sql->execute();

        // Si falla
        if (!$resultado) {
            return -1;
        }

        return $this->conn->insert_id;
    }

    /**
     * Edita los datos de una película existente según su ID
     * @param string $titulo_pelicula
     * @param string $descripcion
     * @param string $director
     * @param int $anio
     * @param int $duracion
     * @param float $precio
     * @param boolean $disponible
     * @param int $pelicula_id
     * @return bool True al editarse correctamente, False al fallar
     */
    public function actualizarPelicula($titulo_pelicula, $descripcion, $director, $anio, $duracion, $precio, $disponible, $pelicula_id)
    {
        $sql = $this->conn->prepare("UPDATE pelicula SET titulo = ?, descripcion = ?, director = ?, anio = ?, duracion = ?, precio = ?, disponible = ? WHERE id = ?");
        $disponible = (int)$disponible;
        $sql->bind_param("sssiidii", $titulo_pelicula, $descripcion, $director, $anio, $duracion, $precio, $disponible, $pelicula_id);

        return $sql->execute();
    }

    
    /**
     * Cambia el estado de disponibilidad de una película según su ID
     * @param int $peliculaId
     * @param bool $estado
     * @return bool
     */
    public function cambiarEstado(int $peliculaId, bool $estado)
    {
        $sql = $this->conn->prepare("UPDATE pelicula SET disponible = ? WHERE id = ?");
        $sql->bind_param("ii", $estado, $peliculaId);
        return $sql->execute();
    }

    /**
     * Agregar un genero existente a una película
     * @param int $pelicula_id
     * @param int $genero_id
     */
    public function agregarGeneroPelicula($pelicula_id, $genero_id)
    {
        $sql = $this->conn->prepare("INSERT INTO pelicula_genero (pelicula_id, genero_id) VALUES (?, ?)");
        $sql->bind_param("ii", $pelicula_id, $genero_id);
        return $sql->execute();
    }
}
