<?php 
    /**
     * Clase que gestiona los géneros de las películas
     */
    class Genero {

        private $conn;

        /**
         * Constructor de la clase Género
         * @param mixed $conn
         */
        public function __construct($conn)
        {
            $this->conn = $conn;
        }

        /**
         * Función que lista todos los Géneros 
         * @return array
         */
        public function listarGenero() {
            $resultado = $this->conn->query("SELECT * FROM genero");

            $generos = [];
            while ($fila = $resultado->fetch_assoc()) {
                $generos[] = $fila;
            }

            return $generos;
        }

        /**
         * Función que agrega más géneros a la BD
         * @param mixed $nombre
         */
        public function agregarGenero($nombre) {
            $sql = $this->conn->prepare("INSERT INTO genero(nombre) VALUES(?)");
            $sql->bind_param("s", $nombre);

            return $sql->execute();
        }

        /**
         * Función que edita los datos de los Géneros
         * @param mixed $id ID del género
         * @param mixed $nombre Nombre del genero
         */
        public function editarGenero($id, $nombre) {
            $sql = $this->conn->prepare("UPDATE genero SET nombre = ? WHERE id = ?");
            $sql->bind_param("si", $nombre, $id);

            return $sql->execute();
        }

        /**
         * Función que elimina un género según su ID
         * @param mixed $id ID del género
         */
        public function eliminarGenero($id) {
            $sql = $this->conn->prepare("DELETE FROM genero WHERE id = ?");
            $sql->bind_param("i", $id);

            return $sql->execute();
        }

        /**
         * Función que devuelve una lista de géneros según el ID de la película
         * @param int $id_pelicula ID de la película
         * @return array Lista de generos
         */
        public function mostrarGenerosPelicula($id_pelicula) {
            $sql = $this->conn->prepare("SELECT g.nombre FROM genero g JOIN pelicula_genero pg ON g.id = pg.genero_id WHERE pg.pelicula_id = ?");
            $sql->bind_param("i", $id_pelicula);

            $resultado = $sql->execute();
            $generos = [];

            while ($fila = $resultado->fetch_assoc()) {
                $generos[] = $fila['nombre'];
            }

            return $generos;
        }
    }

?>