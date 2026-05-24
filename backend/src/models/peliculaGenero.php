<?php

namespace App\Models;

/**
 * Clase PeliculaGenero
 * Gestiona la relación entre películas y géneros
 * Usa una conexion mysqli mediante inyección de dependencias
 */
class PeliculaGenero
{

    private $conn;

    /**
     * Constructor de la clase PeliculaGenero
     * @param mysqli $conn Conexión a la base de datos
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Agrega un género a una película específica
     * @param int $peliculaId ID de la película
     * @param int $generoId ID del género
     * @return bool True si se agregó el género correctamente, False en caso contrario
     */
    public function agregarGenero(int $peliculaId, int $generoId)
    {
        $sql = $this->conn->prepare("INSERT INTO pelicula_genero (pelicula_id, genero_id) VALUES (?, ?)");
        $sql->bind_param("ii", $peliculaId, $generoId);
        return $sql->execute();
    }

    /**
     * Quita un género de una película específica
     * @param int $peliculaId ID de la película
     * @param int $generoId ID del género
     * @return bool True si se quitó el género correctamente, False en caso contrario
     */
    public function quitarGenero(int $peliculaId, int $generoId)
    {
        $sql = $this->conn->prepare("DELETE FROM pelicula_genero WHERE pelicula_id = ? AND genero_id = ?");
        $sql->bind_param("ii", $peliculaId, $generoId);
        return $sql->execute();
    }

    /**
     * Obtiene los géneros asociados a una película específica
     * @param int $peliculaId ID de la película
     * @return array Lista de géneros asociados a la película
     */
    public function obtenerGenerosDePelicula(int $peliculaId)
    {
        $sql = $this->conn->prepare("SELECT g.id, g.nombre FROM genero g INNER JOIN pelicula_genero pg ON g.id = pg.genero_id WHERE pg.pelicula_id = ?");
        $sql->bind_param("i", $peliculaId);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
