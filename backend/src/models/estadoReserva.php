<?php

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
     * Obtiene el ID del estado de reserva segun el nombre
     * @param string $nombre
     * @return int
     */
    public function obtenerIdPorNombre($nombre)
    {
        $sql = $this->conn->prepare("SELECT * FROM estado_reserva WHERE nombre = ?");
        $sql->bind_param("s", $nombre);
        $sql->execute();
        $resultado = $sql->get_result()->fetch_assoc();
        return $resultado ? $resultado['id'] : null;
    }

    /**
     * Obtiene el nombre del estado segun el ID
     * @param mixed $estado_id
     * @return void
     */
    public function obtenerEstado($estado_id)
    {
        $sql = $this->conn->prepare("SELECT nombre FROM estado_reserva WHERE id = ?");
        $sql->bind_param("i", $estado_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }
}
