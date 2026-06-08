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
     * @return array
     */
    public function listar()
    {
        $result = $this->conn->query("SELECT * FROM funcion");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener una función (Función de película) por ID
     * @param int $id ID de función 
     */
    public function obtenerPorId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM funcion WHERE id = ? LIMIT 1");
        $sql->bind_param("i", $id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Crear una nueva función
     * @param int $programacion_id ID película
     * @param string $fecha_hora Hora de estreno
     * @param int $estado_id Fecha in
     */
    public function crear($programacion_id, $fecha_hora, $estado_id)
    {
        $sql = $this->conn->prepare("INSERT INTO funcion (programacion_id, fecha_hora, estado_id) VALUES (?, ?, ?)");
        $sql->bind_param("isi", $programacion_id, $fecha_hora, $estado_id);
        $resultado = $sql->execute();

        if (!$resultado) {
            return -1;
        }
        return $this->conn->insert_id;
    }
    /**
     * Actualiza los datos de una función existente
     * @param int $id
     * @param int $programacion_id ID película
     * @param string $fecha_hora Hora de estreno
     * @param int $estado_id Fecha in
     * @return bool
     */
    public function actualizar($id, $programacion_id, $fecha_hora, $estado_id)
    {
        $sql = $this->conn->prepare("UPDATE funcion SET programacion_id = ?, fecha_hora = ?, estado_id = ? WHERE id = ?");
        $sql->bind_param("isii", $programacion_id, $fecha_hora, $estado_id, $id);
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
     * estado_id (1 ->activa, 2 -> cancelada, 3 -> finalizada)
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
     * Cambia el estado de una funcion
     * @param int $id
     * @param int $estado_id
     * @return bool
     */
    public function cambiarEstado($id, $estado_id)
    {
        $sql = $this->conn->prepare("UPDATE funcion SET estado_id = ? WHERE id = ?");
        $sql->bind_param("ii", $estado_id, $id);
        return $sql->execute();
    }

    /**
     * Obtener funciones por programacion
     * @param mixed $programacion_id
     */
    public function obtenerPorProgramacion($programacion_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM funcion WHERE programacion_id = ?");
        $sql->bind_param("i", $programacion_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Filtrar funciones por película
     * @param int $pelicula_id ID pelicula
     */
    public function obtenerPorPelicula($pelicula_id)
    {
        $sql = $this->conn->prepare("SELECT f.* FROM funcion f INNER JOIN programacion p ON f.programacion_id = p.id WHERE p.pelicula_id = ?");
        $sql->bind_param("i", $pelicula_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * 
     * @param int $programacion_id
     */
    public function eliminarPorProgramacion($programacion_id)
    {
        $sql = $this->conn->prepare("DELETE FROM funcion WHERE programacion_id = ?");
        $sql->bind_param("i", $programacion_id);
        return $sql->execute();
    }
}
