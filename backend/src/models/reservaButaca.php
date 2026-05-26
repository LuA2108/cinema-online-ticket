<?php

namespace App\Models;

/**
 * Modelo ReservaButaca
 * Gestiona la relación entre:
 * - butaca
 * - reserva
 * - función
 */
class ReservaButaca
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Asigna una butaca a una reserva
     * @param int $butaca_id
     * @param int $funcion_id
     * @param float $precio
     */
    public function crear($butaca_id, $reserva_id, $funcion_id, $precio)
    {
        $sql = $this->conn->prepare("INSERT INTO reserva_butaca (butaca_id, reserva_id, funcion_id, precio) VALUES (?, ?, ?, ?) ");
        $sql->bind_param("iiid", $butaca_id, $reserva_id, $funcion_id, $precio);

        if ($sql->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Obtiene butacas de una reserva
     * @param int $reserva_id
     */
    public function obtenerPorReserva($reserva_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM reserva_butaca WHERE reserva_id = ?");
        $sql->bind_param("i", $reserva_id);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene butacas ocupadas por función
     * @param int $funcion_id
     */
    public function obtenerPorFuncion($funcion_id)
    {
        $sql = $this->conn->prepare("SELECT butaca_id FROM reserva_butaca WHERE funcion_id = ?");
        $sql->bind_param("i", $funcion_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Elimina una butaca de la reserva de una funcion
     * @param int $reserva_id
     * @param int $funcion_id
     * @param int $butaca_id
     */
    public function eliminar($reserva_id, $funcion_id, $butaca_id)
    {
        $sql = $this->conn->prepare("DELETE FROM reserva_butaca WHERE reserva_id = ? AND funcion_id = ? AND butaca_id = ?");

        $sql->bind_param("iii", $reserva_id, $funcion_id, $butaca_id);
        return $sql->execute();
    }

    /**
     * Verifica si una butaca está ocupada en una función
     * @param int $funcion_id
     * @param int $butaca_id
     */
    public function estaOcupada($funcion_id, $butaca_id)
    {
        $sql = $this->conn->prepare("SELECT COUNT(*) as total FROM reserva_butaca WHERE funcion_id = ? AND butaca_id = ?");
        $sql->bind_param("ii", $funcion_id, $butaca_id);
        $sql->execute();

        $result = $sql->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }
}
