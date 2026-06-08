<?php

namespace App\Models;

/**
 * Clase Sala
 * Gestiona metodos CRUD, excepto delete (desactiva en vez de eliminar)
 * Conexión mysqli mediante inyeccion de dependencias
 */
class Sala
{
    private $conn;

    /**
     * Constructor de la clase Sala
     * @param mixed $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene una lista de todas las salas
     * @return array Lista de las salas
     */
    public function listarSalas()
    {
        $sql = $this->conn->query("SELECT * FROM sala");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene una sala por su ID
     * @param int $sala_id
     * @return array Sala encontrada o null si no existe
     */
    public function listarSala($sala_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM sala WHERE id = ?");
        $sql->bind_param("i", $sala_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Obtiene una lista de todas por estado
     * @param boolean $estado
     */
    public function listarSalaPorEstado($estado)
    {
        $sql = $this->conn->prepare("SELECT * FROM sala WHERE activa = ?");

        $estado = $estado ? 1 : 0;

        $sql->bind_param("i", $estado);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Agrega una nueva sala
     * @param int $numero Numero de sala
     * @param int $filas las filas presentes de la sala
     * @param int $butacaPorFila cantidad de butacas por fila
     * @param boolean $activa 
     * @return int ID de la sala creada, -1 si hubo un error
     */
    public function agregarSala($numero, $filas, $butacaPorFila, $activa)
    {
        $activa = (int)$activa;
        $sql = $this->conn->prepare("INSERT INTO sala(numero, filas, butacas_por_fila, activa) VALUES(?, ?, ?, ?)");
        $sql->bind_param("iiii", $numero, $filas, $butacaPorFila, $activa);
        $resultado = $sql->execute();

        if($resultado) {
            return $this->conn->insert_id;
        }
        return -1;
    }

    /**
     * Desactiva una sala existente
     * @param int $sala_id ID de la sala
     * @param boolean $activa
     * @return bool True al ser desactivada, false al fallar
     */
    public function cambiarEstadoSala($sala_id, $activa)
    {
        $activa = (int)$activa;
        $sql = $this->conn->prepare("UPDATE sala SET activa = ? WHERE id = ?");
        $sql->bind_param("ii", $activa, $sala_id);
        return $sql->execute();
    }

}
