<?php

namespace App\Models;

/**
 * Clase PeliculaImagen
 * Gestiona las imágenes asociadas a películas con metodos CRUD, ademas de filtrados
 * Conexión con mysqli mediante inyección de dependencias
 */
class PeliculaImagen
{
    private $conn;

    /**
     * Constructor de la clase Película imagen
     * @param mysqli $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Funcion que lista todas las imagenes de todas las peliculas, ordenadas por pelicula_id y tipo
     * @return array Lista de todas las imagenes
     */
    public function listar()
    {
        $sql = "SELECT * FROM pelicula_imagen ORDER BY pelicula_id, tipo";

        $resultado = $this->conn->query($sql);
        $rows = $resultado->fetch_all(MYSQLI_ASSOC);
        $peliculas = [];

        foreach ($rows as $row) {
            $peliculas[$row['pelicula_id']][] = $row;
        }

        return $peliculas;
    }

    /**
     * Obtiene los datos de una imagen de película según su ID
     * @param int $imagen_id
     * @return array|null Devuelve un array asociativo con los datos de la imagen o null si no existe
     */
    public function obtenerImagen($imagen_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM pelicula_imagen WHERE id = ?");
        $sql->bind_param("i", $imagen_id);
        $sql->execute();
        $resultado = $sql->get_result()->fetch_assoc();
        return $resultado;
    }

    /**
     * Función que obtiene todas las imágenes asociadas a una película específica
     * @param int $pelicula_id ID de película
     * @return array Lista de datos del ID de la película
     */
    public function obtenerPorPelicula($pelicula_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM pelicula_imagen WHERE pelicula_id = ?");
        $sql->bind_param("i", $pelicula_id);
        $sql->execute();

        $resultado = $sql->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function existeUrl(int $peliculaId, string $url): bool
    {
        $sql = $this->conn->prepare(
            "SELECT 1 FROM pelicula_imagen WHERE pelicula_id = ? AND url = ? LIMIT 1"
        );

        $sql->bind_param("is", $peliculaId, $url);
        $sql->execute();

        return $sql->get_result()->fetch_assoc() !== null;
    }

    /**
     * Función que agrega una imagen asociada a una película en la base de datos
     * @param int $pelicula_id ID de película
     * @param string $tipo Tipo de imagen (poster, banner, ...)
     * @param string $url URL o enlace de la imgen
     * @return int ID de la imagen creada o -1 si hubo un error
     */
    public function crear($pelicula_id, $tipo, $url)
    {
        $sql = $this->conn->prepare("INSERT INTO pelicula_imagen (pelicula_id, tipo, url) VALUES (?, ?, ?)");
        $sql->bind_param("iss", $pelicula_id, $tipo, $url);
        if ($sql->execute()) {
            return $this->conn->insert_id;
        }
        return -1;
    }

    /**
     * Función que elimina una imagen asociada a una película según el ID de la imagen
     * @param int $id
     * @return bool True si la eliminacion fue exitosa, False en caso contrario
     */
    public function eliminar($id)
    {
        $sql = $this->conn->prepare("DELETE FROM pelicula_imagen WHERE id = ?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    /**
     * Función que obtiene una imagen de una película según su tipo
     * @param int $pelicula_id ID de la película
     * @param string $tipo Tipo de imagen (poster, banner, etc.)
     * @return array|null Devuelve un array asociativo con los datos de la imagen o null si no existe
     */
    public function obtenerPorTipo($pelicula_id, $tipo)
    {
        $sql = $this->conn->prepare("SELECT * FROM pelicula_imagen WHERE pelicula_id = ? AND tipo = ?");
        $sql->bind_param("is", $pelicula_id, $tipo);
        $sql->execute();

        $resultado = $sql->get_result();
        return $resultado->fetch_assoc(); // uno solo (banner o poster)
    }
}
