<?php
namespace App\Models;
/**
 * Clase Estado de Reserva
 * Gestiona las consultas de los estados de Reserva
 * Conexión mysqli mediante inyección de dependencia
 */
class EstadoReserva
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
     * Obtiene todos los estados de reserva
     * @return array
     */
    public function obtenerEstados()
    {
        $sql = $this->conn->query("SELECT * FROM estado_reserva");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene el datos del estado segun el ID
     * @param mixed $estado_id
     */
    public function obtenerEstado($estado_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM estado_reserva WHERE id = ?");
        $sql->bind_param("i", $estado_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc() ?: null;
    }
}
