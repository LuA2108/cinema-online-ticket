<?php 
namespace App\Models;
    class Reserva {

        private $conn;

        /**
         * Constructor de la clase
         * @param mysqli $conn conexión a la BD
         */
        public function __construct($conn)
        {
            $this->conn = $conn;
        }

        /**
         * Función que devuelve una lista de todas las reservass
         * @return array Lista de reservas
         */
        public function listarReservas() {
            $resultado = $this->conn->query("SELECT * FROM reserva");

            $reservas = [];

            while ($fila = $resultado->fetch_assoc()) {
                $reservas[]= $fila;
            }

            return $reservas;
        }
        
        /**
         * Función que busca los datos de una reserva por el ID del usuario
         * @param int $usuario_id ID del usuario
         */
        public function buscarReservaUsuario($usuario_id) {
            $sql = $this->conn->prepare("SELECT * FROM reserva WHERE usuario_id = ?");
            $sql -> bind_param("i", $usuario_id);
            $sql->execute();

            return $sql ->get_result()->fetch_assoc();
        }

        /**
         * Funcion que busca reserva por Email
         * @param string $email Email del usuario
         */
        public function reservaEmail($email) {
            $sql = $this->conn->prepare("SELECT * FROM reserva WHERE email_cliente = ?");
            $sql -> bind_param("s", $email);
            $sql -> execute();

            return $sql->get_result()->fetch_assoc();

        }
    }

?>