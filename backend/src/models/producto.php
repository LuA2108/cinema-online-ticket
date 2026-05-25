<?php
namespace App\Models;
/**
 * Clase Producto
 * Gestiona metodos CRUD ademas de filtrados
 * Conexión mysqli mediante inyección de dependencias
 */
class Producto
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
     * Obtiene todos los productos de la base de datos
     * @return array Lista de productos
     */
    public function listarProductos()
    {
        $sql = "SELECT p.id, p.nombre, p.precio, p.comentario, p.create_time, t.nombre AS tipo FROM producto p INNER JOIN tipo_producto t ON p.tipo_id = t.id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene productos filtrados por tipo
     * @param string $tipoId ID de Tipo de producto
     * @return array Lista de productos filtrados
     */
    public function listarProductosPorTipo($tipoId)
    {
        $sql = "SELECT p.id, p.nombre, p.precio, p.comentario, p.create_time, t.nombre AS tipo FROM producto p INNER JOIN tipo_producto t ON p.tipo_id = t.id WHERE p.tipo_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $tipoId);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene un producto específico por su ID
     * @param int $id Identificador del producto
     * @return array|null Producto encontrado o null
     */
    public function obtenerProductoPorId($id)
    {
        $sql = "SELECT * FROM producto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Busca productos por nombre (coincidencia parcial)
     * @param string $nombre Nombre o parte del nombre
     * @return array Lista de productos encontrados
     */
    public function obtenerProductoPorNombre($nombre)
    {
        $sql = "SELECT * FROM producto WHERE nombre LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $like = "%$nombre%";
        $stmt->bind_param("s", $like);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Inserta un nuevo producto en la base de datos
     * @param string $nombre
     * @param float $precio
     * @param int $tipoId
     * @param string $comentario
     * @return bool true si se insertó correctamente, false si falla
     */
    public function agregarProducto($nombre, $precio, $tipoId, $comentario)
    {
        $sql = "INSERT INTO producto (nombre, precio, tipo_id, comentario) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdis", $nombre, $precio, $tipoId, $comentario);

        return $stmt->execute();
    }

    /**
     * Actualiza un producto existente
     * @param int $id ID del producto
     * @param string $nombre Nombre del producto
     * @param float $precio Precio del producto
     * @param int $tipoId ID de Tipo de producto
     * @param string $comentario
     * @return bool true si se actualizó correctamente
     */
    public function actualizarProducto($id, $nombre, $precio, $tipoId, $comentario)
    {
        $sql = "UPDATE producto SET nombre = ?, precio = ?, tipo_id = ?, comentario = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdisi", $nombre, $precio, $tipoId, $comentario, $id);

        return $stmt->execute();
    }

    /**
     * Elimina un producto por su ID
     * @param int $id
     * @return bool true si se eliminó correctamente
     */
    public function eliminarProducto($id)
    {
        $sql = "DELETE FROM producto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
