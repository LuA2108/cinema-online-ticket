<?php

namespace App\Models;

use mysqli;

class Reserva
{

    private mysqli $conn;

    /**
     * Constructor de la clase
     * Recibe la conexión a la base de datos (mysqli)
     * @param mysqli $conn
     *
    */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Función que devuelve una lista de todas las reservass
     * @return array Lista de reservas
     */
    public function listarReservas()
    {
        $resultado = $this->conn->query("SELECT * FROM reserva");
        $reservas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $reservas[] = $fila;
        }
        return $reservas;
    }

    /**
     * Obtiene una reserva por su ID
     * @param int $id ID de la reserva
     * @return array|null Datos de la reserva o null
     */
    public function obtenerReservaPorId($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM reserva WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Función que busca los datos de una reserva por el ID del usuario
     * @param int $usuario_id ID del usuario
     * @return array Lista de reservas del usuario
     */
    public function buscarReservaUsuario($usuario_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM reserva WHERE usuario_id = ?");
        $sql->bind_param("i", $usuario_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Funcion que busca reserva por Email
     * @param string $email Email del usuario
     * @return array Lista de reservas
     */
    public function reservaEmail($email)
    {
        $sql = $this->conn->prepare("SELECT * FROM reserva WHERE email_cliente = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene reservas por función
     * @param int $funcion_id ID de la función
     * @return array Lista de reservas
     */
    public function obtenerPorFuncion($funcion_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM reserva WHERE funcion_id = ?");
        $sql->bind_param("i", $funcion_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea una reserva
     * @param mixed $usuario_id
     * @param mixed $nombre_cliente
     * @param mixed $email_cliente
     * @param mixed $funcion_id
     * @param mixed $estado_id
     * @param mixed $total
     */
    public function crearReserva($usuario_id, $nombre_cliente, $email_cliente, $funcion_id, $estado_id, $total)
    {

        $sql = $this->conn->prepare("INSERT INTO reserva(usuario_id, nombre_cliente, email_cliente, funcion_id, estado_id, total) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("issiid", $usuario_id, $nombre_cliente, $email_cliente, $funcion_id, $estado_id, $total);
        $sql->execute();

        if ($sql->affected_rows > 0) {
            return $this->conn->insert_id;
        }

        return -1;
    }

    /**
     * Elimina una reserva por ID
     * @param int $id ID de la reserva
     * @return bool resultado de la operación
     */
    public function eliminarReserva($id)
    {
        $sql = $this->conn->prepare("DELETE FROM reserva WHERE id = ?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    /**
     * Actualiza el estado de una reserva
     * @param int $id ID de la reserva
     * @param int $estado_id nuevo estado
     * @return bool resultado de la operación
     */
    public function actualizarEstado($id, $estado_id)
    {
        $sql = $this->conn->prepare("UPDATE reserva SET estado_id = ? WHERE id = ?");
        $sql->bind_param("ii", $estado_id, $id);
        return $sql->execute();
    }

    /**
     * Actualiza el total de una reserva
     * @param int $reserva_id ID de la reserva
     * @param float $total nuevo total
     * @return bool resultado de la operación
     */
    public function actualizarTotal($reserva_id, $total)
    {
        $sql = $this->conn->prepare("UPDATE reserva SET total = ? WHERE id = ?");
        $sql->bind_param("di", $total, $reserva_id);
        return $sql->execute();
    }
}
