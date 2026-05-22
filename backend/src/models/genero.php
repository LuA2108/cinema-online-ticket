<?php 
namespace App\Models;
    /**
     * Clase Genero
     * Gestiona operaciones CRUD de géneros de películas
     * Usa una conexion mysqli mediante inyección de dependencias
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
         * Obtiene una lista de todos los Géneros 
         * @return array
         */
        public function listarGeneros() {
            $resultado = $this->conn->query("SELECT * FROM genero");

            $generos = [];
            while ($fila = $resultado->fetch_assoc()) {
                $generos[] = $fila;
            }

            return $generos;
        }

        /**
         * Agrega más géneros a la BD
         * @param string $nombre
         */
        public function agregarGenero($nombre) {
            $sql = $this->conn->prepare("INSERT INTO genero(nombre) VALUES(?)");
            $sql->bind_param("s", $nombre);

            return $sql->execute();
        }

        /**
         * Edita un genero existente según su ID
         * @param int $id ID del género
         * @param string $nombre Nombre del genero
         */
        public function editarGenero($id, $nombre) {
            $sql = $this->conn->prepare("UPDATE genero SET nombre = ? WHERE id = ?");
            $sql->bind_param("si", $nombre, $id);

            return $sql->execute();
        }

        /**
         * Elimina un género según su ID
         * @param int $id ID del género
         */
        public function eliminarGenero($id) {
            $sql = $this->conn->prepare("DELETE FROM genero WHERE id = ?");
            $sql->bind_param("i", $id);

            return $sql->execute();
        }

        /**
         * Devuelve una lista de géneros según el ID de la película
         * @param int $id_pelicula ID de la película
         * @return array Lista de generos
         */
        public function mostrarGenerosPelicula($id_pelicula) {
            $sql = $this->conn->prepare("SELECT g.nombre FROM genero g JOIN pelicula_genero pg ON g.id = pg.genero_id WHERE pg.pelicula_id = ?");
            $sql->bind_param("i", $id_pelicula);
            $sql->execute();

            $resultado = $sql->get_result();
            $generos = [];

            while ($fila = $resultado->fetch_assoc()) {
                $generos[] = $fila['nombre'];
            }

            return $generos;
        }
    }

?>