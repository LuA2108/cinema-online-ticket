<?php

/**
 * Clase TipoProducto
 *  Proporciona métodos CRUD y consultas de búsqueda
 * COnexión mysqli mediante inyección de dependencias
 */
class TipoProducto
{
    private $conn;
    /**
     * Constructor de la clase
     * @param mysqli $conn conexión a la BD
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene todos los tipos de productos
     * @return array Lista de tipos de producto
     */
    public function listar()
    {
        $sql = $this->conn->query("SELECT * FROM tipo_producto");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene un tipo de producto por su ID
     * @param int $id ID del tipo de producto
     * @return array|null Retorna el registro o null si no existe
     */
    public function obtenerPorId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM tipo_producto WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Crea un nuevo tipo de producto
     * @param string $nombre Nombre del tipo de producto
     * @param string $descripcion Descripción del tipo
     * @return bool True si se creó correctamente, false en caso contrario
     */
    public function crear($nombre, $descripcion)
    {
        $sql = $this->conn->prepare(
            "INSERT INTO tipo_producto (nombre, descripcion) VALUES (?, ?)"
        );

        $sql->bind_param("ss", $nombre, $descripcion);
        return $sql->execute();
    }

    /**
     * Actualiza un tipo de producto existente
     * @param int $id ID del tipo de producto
     * @param string $nombre Nuevo nombre
     * @param string $descripcion Nueva descripción
     * @return bool True si se actualizó correctamente
     */
    public function actualizar($id, $nombre, $descripcion)
    {
        $sql = $this->conn->prepare(
            "UPDATE tipo_producto SET nombre = ?, descripcion = ? WHERE id = ?"
        );

        $sql->bind_param("ssi", $nombre, $descripcion, $id);
        return $sql->execute();
    }

    /**
     * Elimina un tipo de producto por su ID
     * @param int $id ID del tipo de producto
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id)
    {
        $sql = $this->conn->prepare(
            "DELETE FROM tipo_producto WHERE id = ?"
        );

        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    /**
     * Busca tipos de producto por nombre (coincidencia parcial)
     * @param string $nombre Texto a buscar
     * @return array Lista de resultados coincidentes
     */
    public function buscarPorNombre($nombre)
    {
        $sql = $this->conn->prepare(
            "SELECT * FROM tipo_producto WHERE nombre LIKE ?"
        );

        $like = "%$nombre%";
        $sql->bind_param("s", $like);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
