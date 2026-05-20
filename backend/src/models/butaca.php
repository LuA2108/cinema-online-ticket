<?php 
    /**
     * Clase Butaca
     * Lee datos de butaca
     * Usa una conexion mysqli mediante inyección de dependencias
     */
    class Butaca {
        private $conn;

        public function __construct($conn)
        {
            $this->conn = $conn;
        }

        /**
         * Summary of obtenerPorSala
         * @param mixed $sala_id
         */
        public function obtenerPorSala($sala_id) {
            $sql = $this->conn->prepare("SELECT * FROM butaca WHERE sala_id = ?");
            $sql->bind_param("i", $sala_id);
            $sql->execute();
            
            return $sql->get_result()->fetch_assoc();
        }
    }
?>