<?php 
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
         * Obtener ID por nombre         
         * @param string $nombre
         */
        public function getIdPorNombre($nombre)
        {
            $sql = $this->conn->prepare("
                SELECT id FROM estado_funcion WHERE nombre = ?
            ");

            $sql->bind_param("s", $nombre);
            $sql->execute();

            $res = $sql->get_result()->fetch_assoc();

            return $res ? (int)$res['id'] : null;
        }

        /**
         * Obtener nombre por ID
         * @param int $id
         */
        public function getNombrePorId($id)
        {
            $sql = $this->conn->prepare("SELECT nombre FROM estado_funcion WHERE id = ?");
            $sql->bind_param("i", $id);
            $sql->execute();

            return $sql->get_result()->fetch_assoc();
        }
    }

?>