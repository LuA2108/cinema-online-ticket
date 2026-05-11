<?php 
    /**
     * Clase que gestiona las operaciones CRUD de películas
     */
    class Pelicula {
        private $conn;

        /**
         * Constructor de la clase película
         * @param mysqli $conn Conexión a la BD
         */
        public function __construct($conn) {
            $this->conn = $conn;
        }

        /**
         * Función que devuelve una lista de todas las películas
         */
        public function listarPeliculas() {
            $sql = "SELECT * FROM peliculas";
            $resultado = $this->conn->query($sql);

            $peliculas = [];

            while ($fila = $resultado->fetch_assoc()) {
                $peliculas[] = $fila;
            }

            return $peliculas;
        }

        /**
         * Función que busca una película por ID
         * @param int $id ID de la película
         */
        public function peliculaId($id) {
            $sql = $this->conn->prepare("SELECT * FROM peliculas WHERE id = ?");
            $sql -> bind_param("i", $id );
            $sql -> execute();

            return $sql->get_result()->fetch_assoc();
        }
    }
?>