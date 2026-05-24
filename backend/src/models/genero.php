<?php

namespace App\Models;

/**
 * Clase Genero
 * Gestiona operaciones CRUD de géneros de películas
 * Usa una conexion mysqli mediante inyección de dependencias
 */
class Genero
{
    private $conn;

    /**
     * Constructor de la clase Género
     * @param mysqli $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtener todos los géneros
     * @return array
     */
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM genero";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener género por ID
     * @param int $id
     * @return array|null
     */
    public function obtenerPorId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM genero WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Crear género
     * @param string $nombre
     * @return bool
     */
    public function crearGenero($nombre)
    {
        $sql = $this->conn->prepare("INSERT INTO genero(nombre)VALUES(?)");
        $sql->bind_param("s", $nombre);
        return $sql->execute();
    }

    /**
     * Actualizar género
     * @param int $id
     * @param string $nombre
     * @return bool
     */
    public function actualizarGenero($id, $nombre)
    {
        $sql = $this->conn->prepare("UPDATE generoSET nombre = ?WHERE id = ?");
        $sql->bind_param("si", $nombre, $id);
        return $sql->execute();
    }

    /**
     * Eliminar género
     * @param int $id
     * @return bool
     */
    public function eliminarGenero($id)
    {
        $sql = $this->conn->prepare("DELETE FROM genero WHERE id = ?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }
}
