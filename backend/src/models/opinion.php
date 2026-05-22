<?php
namespace App\Models;
/**
 * Clase Opinión
 * Gestiona metodos CRUD de la entidad
 * Conexión mysqli mediante inyección de dependencia
 */
class Opinion
{
    private $conn;

    /**
     * Constructor
     * @param mysqli $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene todos los comentarios
     * @return array Lista de comentarios
     */
    public function listarComentarios()
    {
        $sql = $this->conn->query("SELECT * FROM opinion");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene comentarios según el usuario
     * @param int $usuario_id
     * @return array Lista de comentarios
     */
    public function listarComentariosPorUsuario($usuario_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM opinion WHERE usuario_id = ?");
        $sql->bind_param("i", $usuario_id);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene comentarios según la película
     * @param int $pelicula_id
     * @return array Lista de comentarios
     */
    public function listarComentariosPorPelicula($pelicula_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM opinion WHERE pelicula_id = ?");
        $sql->bind_param("i", $pelicula_id);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea un nuevo comentario
     * @param int $pelicula_id
     * @param int $usuario_id
     * @param string $comentario
     * @return bool
     */
    public function crearComentario($pelicula_id, $usuario_id, $comentario)
    {
        $sql = $this->conn->prepare(
            "INSERT INTO opinion(pelicula_id, usuario_id, comentario) VALUES(?, ?, ?)"
        );

        $sql->bind_param(
            "iis",
            $pelicula_id,
            $usuario_id,
            $comentario
        );

        return $sql->execute();
    }

    /**
     * Actualiza un comentario existente
     * @param int $comentario_id
     * @param int $pelicula_id
     * @param int $usuario_id
     * @param string $comentario
     * @return bool
     */
    public function actualizarComentario($comentario_id, $pelicula_id, $usuario_id, $comentario) 
    {
        $sql = $this->conn->prepare("UPDATE opinion SET pelicula_id = ?, usuario_id = ?, comentario = ? WHERE id = ?");
        $sql->bind_param("iisi",$pelicula_id, $usuario_id, $comentario, $comentario_id);
        return $sql->execute();
    }

    /**
     * Elimina un comentario según su ID
     * @param int $comentario_id
     * @return bool
     */
    public function eliminarComentario($comentario_id)
    {
        $sql = $this->conn->prepare("DELETE FROM opinion WHERE id = ?");
        $sql->bind_param("i", $comentario_id);
        return $sql->execute();
    }
}
