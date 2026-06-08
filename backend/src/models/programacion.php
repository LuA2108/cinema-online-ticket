<?php

namespace App\Models;

/**
 * Clase Programacion
 * Gestiona operaciones CRUD de la tabla programacion
 */
class Programacion
{
    private $conn;


    /**
     * @param mysqli $conn
     */
    public function __construct($conn)
    {

        $this->conn = $conn;
    }

    /**
     * Obtiene todas las programaciones
     * @return array
     */
    public function listarProgramaciones()
    {
        $sql = $this->conn->query("SELECT * FROM programacion ORDER BY fecha_inicio ASC, hora ASC");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene una programación por ID
     * @param int $id
     * @return array|null
     */
    public function obtenerProgramacion($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM programacion WHERE id = ? LIMIT 1");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Lista programaciones por estado
     * @param bool $estado
     * @return array
     */
    public function listarPorEstado($estado)
    {
        $estado = (int)$estado;

        $sql = $this->conn->prepare("SELECT * FROM programacion WHERE estado = ? ORDER BY fecha_inicio ASC");
        $sql->bind_param("i", $estado);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lista programaciones de una película
     * @param int $pelicula_id
     * @return array
     */
    public function listarPorPelicula($pelicula_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM programacion WHERE pelicula_id = ? ORDER BY fecha_inicio ASC");
        $sql->bind_param("i", $pelicula_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lista programaciones de una sala
     * @param int $sala_id
     * @return array
     */
    public function listarPorSala($sala_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM programacion WHERE sala_id = ? ORDER BY fecha_inicio ASC");
        $sql->bind_param("i", $sala_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Inserta una programación
     * @param int $pelicula_id
     * @param int $sala_id
     * @param mixed $hora
     * @param mixed $fecha_inicio
     * @param mixed $fecha_fin
     * @param float $precio
     * @param boolean $estado
     */
    public function agregarProgramacion($pelicula_id, $sala_id, $hora, $fecha_inicio, $fecha_fin, $precio, $estado = false)
    {
        $estado = (int)$estado;
        $sql = $this->conn->prepare("INSERT INTO programacion(pelicula_id, sala_id, hora, fecha_inicio, fecha_fin, precio,estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("iisssdi", $pelicula_id, $sala_id, $hora, $fecha_inicio, $fecha_fin, $precio, $estado);
        $ok = $sql->execute();

        if ($ok) {
            return $this->conn->insert_id;
        }
        return -1;
    }

    /**
     * Actualiza una programación
     * @param int $id
     * @param int $peliculaId
     * @param int $salaId
     * @param string $hora
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param float $precio
     * @return bool
     */
    public function actualizarProgramacion($id, $peliculaId, $salaId, $hora, $fechaInicio, $fechaFin, $precio): bool
    {
        $sql = $this->conn->prepare("UPDATE programacion SET pelicula_id = ?, sala_id = ?, hora = ?, fecha_inicio = ?, fecha_fin = ?, precio = ? WHERE id = ?");

        $sql->bind_param("iisssdi", $peliculaId, $salaId, $hora, $fechaInicio, $fechaFin, $precio, $id);
        return $sql->execute();
    }

    /**
     * 
     * @param int $sala_id
     * @param string $hora
     * @param string $inicio
     * @param string $fin
     */
    public function existeSolapamiento($sala_id, $hora, $inicio, $fin)
    {
        $sql = $this->conn->prepare("SELECT id FROM programacion WHERE sala_id = ? AND hora = ? AND ( fecha_inicio <= ? AND fecha_fin >= ?) LIMIT 1");
        $sql->bind_param("isss", $sala_id, $hora, $fin, $inicio);
        $sql->execute();

        return $sql->get_result()->fetch_assoc() !== null;
    }

    /**
     * 
     * @param int $id
     * @param int $sala_id
     * @param string $hora
     * @param string $inicio
     * @param string $fin
     * @return bool
     */
    public function existeSolapamientoEdicion($id, $sala_id, $hora, $inicio, $fin)
    {
        $sql = $this->conn->prepare("SELECT id FROM programacion WHERE id != ? AND sala_id = ? AND hora = ? AND fecha_inicio <= ? AND fecha_fin >= ? LIMIT 1");
        $sql->bind_param("iisss", $id, $sala_id, $hora, $fin, $inicio);
        $sql->execute();

        return $sql->get_result()->fetch_assoc() !== null;
    }

    /**
     * Cambia el estado de una programación
     * @param int $id
     * @param bool $estado
     * @return bool
     */
    public function cambiarEstado($id, $estado)
    {
        $estado = (int)$estado;
        $sql = $this->conn->prepare("UPDATE programacion SET estado = ? WHERE id = ?");
        $sql->bind_param("ii", $estado, $id);
        return $sql->execute();
    }

    /**
     * ELiminacion de programacion por ID
     * @param int $programacionId
     */
    public function eliminarProgramacion($programacionId)
    {
        $sql = $this->conn->prepare(("DELETE FROM programacion WHERE id = ?"));
        $sql->bind_param("i", $programacionId);
        return $sql->execute();
    }
}
