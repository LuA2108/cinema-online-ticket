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
     * @param int $reserva_id
     */
    public function crear($butaca_id, $reserva_id): bool
    {
        $sql = $this->conn->prepare("INSERT INTO reserva_butaca (butaca_id, reserva_id) VALUES (?, ?)");
        $sql->bind_param("ii", $butaca_id, $reserva_id);

        return $sql->execute();
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
        $sql = $this->conn->prepare("SELECT rb.butaca_id FROM reserva_butaca rb INNER JOIN reserva r ON rb.reserva_id = r.id WHERE r.funcion_id = ?");
        $sql->bind_param("i", $funcion_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Elimina una butaca de la reserva de una funcion
     * @param int $reserva_id
     * @param int $butaca_id
     */
    public function eliminar($reserva_id, $butaca_id)
    {
        $sql = $this->conn->prepare("DELETE FROM reserva_butaca WHERE reserva_id = ? AND butaca_id = ?");
        $sql->bind_param("ii", $reserva_id, $butaca_id);

        return $sql->execute();
    }

    /**
     * Verifica si una butaca está ocupada en una función
     * @param int $funcion_id
     * @param int $butaca_id
     */
    public function estaOcupada($funcion_id, $butaca_id): bool
    {
        $sql = $this->conn->prepare("SELECT COUNT(*) total FROM reserva_butaca rb INNER JOIN reserva r ON rb.reserva_id = r.id
        WHERE r.funcion_id = ? AND rb.butaca_id = ?");

        $sql->bind_param("ii", $funcion_id, $butaca_id);
        $sql->execute();

        $resultado = $sql->get_result()->fetch_assoc();

        return (float)$resultado['total'] > 0;
    }
}
