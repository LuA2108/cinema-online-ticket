<?php 
namespace App\Models;
    /**
     * Clase de Estados de las Funciones
     * Consultas de lectura
     * Conexión mysqli mediante inyección de dependencia
     */
    class EstadoFuncion {
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
         * Obtener todos los estados
         * @return array
         */
        public function obtenerEstados()
        {
            $sql = $this->conn->query("SELECT * FROM estado_funcion");
            return $sql->fetch_all(MYSQLI_ASSOC);
        }

        /**
         * Obtener estado por ID
         * @param int $id Id del estado
         * @return array|null 
         */
        public function obtenerEstadoPorId($id)
        {
            $sql = $this->conn->prepare("SELECT nombre FROM estado_funcion WHERE id = ?");
            $sql->bind_param("i", $id);
            $sql->execute();

            $respuesta = $sql->get_result()->fetch_assoc();
            return $respuesta ?: null;
        }
    }

?>