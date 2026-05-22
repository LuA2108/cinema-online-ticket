<?php
namespace App\Models;
/**
 * Clase Función
 * Gestiona las funciones (proyecciones) que tiene una pelicula con metodos CRUD y filtrados
 * Conexióon mysqli mediante inyección de dependencias
 */
class Funcion
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
     * Lista todas las funciones con información relacionada
     */
    public function listar()
    {
        $sql = $this->conn->prepare("SELECT f.*, p.titulo AS pelicula, s.numero AS sala, e.nombre AS estado FROM funcion f
            INNER JOIN pelicula p ON f.pelicula_id = p.id
            INNER JOIN sala s ON f.sala_id = s.id
            INNER JOIN estado_funcion e ON f.estado_id = e.id");

        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener una función (Función de película) por ID
     * @param int $id ID de función 
     */
    public function obtenerPorId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM funcion WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Crear una nueva función
     * @param int $pelicula_id ID película
     * @param int $sala_id ID sala
     * @param string $hora Hora de estreno
     * @param string $fecha_inicio Fecha in
     * @param string $fecha_fin
     * @param int $estado_id
     * @return bool
     */
    public function crear($pelicula_id, $sala_id, $hora, $fecha_inicio, $fecha_fin, $estado_id)
    {
        $sql = $this->conn->prepare("INSERT INTO funcion (pelicula_id, sala_id, hora, fecha_inicio, fecha_fin, estado_id) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("iisssi", $pelicula_id, $sala_id, $hora, $fecha_inicio, $fecha_fin, $estado_id);
        return $sql->execute();
    }

    /**
     * Actualiza los datos de una función existente
     * @param int $id
     * @param int $pelicula_id
     * @param int $sala_id
     * @param string $hora
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @param int $estado_id
     * @return bool
     */
    public function actualizar($id, $pelicula_id, $sala_id, $hora, $fecha_inicio, $fecha_fin, $estado_id)
    {
        $sql = $this->conn->prepare("UPDATE funcion SET pelicula_id = ?, sala_id = ?, hora = ?, fecha_inicio = ?, fecha_fin = ?, estado_id = ? WHERE id = ?");
        $sql->bind_param("iiisssi", $pelicula_id, $sala_id, $hora, $fecha_inicio, $fecha_fin, $estado_id, $id);
        return $sql->execute();
    }

    /**
     * Eliminar función
     * @param int $id ID de la función
     */
    public function eliminar($id)
    {
        $sql = $this->conn->prepare("DELETE FROM funcion WHERE id = ?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    /** 
     * Filtrar funciones por estado
     * estado_id (1 ->pendiente, 2 -> cancelada, 3 -> pagado)
     * @param int $estado_id ID del estado de la función
     */
    public function obtenerPorEstado($estado_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM funcion WHERE estado_id = ?");

        $sql->bind_param("i", $estado_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Filtrar funciones por película
     * @param int $pelicula_id ID pelicula
     */
    public function obtenerPorPelicula($pelicula_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM funcion WHERE pelicula_id = ?");
        $sql->bind_param("i", $pelicula_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    
}
