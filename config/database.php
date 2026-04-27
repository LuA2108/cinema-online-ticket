<?php
// config/database.php
/**
 * Clase que maneja la conexion a la BD mediante Mysqli,
 * con metodos para abrir y cerrar la conexion.
 * @author Lucero Anay Cahuana
 */
class Database {

    //Atributos privados para la conexion a la BD
    private $conn; 
    private $host = 'localhost';
    private $database = 'bd_sistema_reserva_cine';
    private $usuario = 'root';
    private $password = '';

    /**
     * Crea la conexion con la BD directamente, 
     * controla los errores por conexión notificando con un mensaje
     */
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->usuario, $this->password, $this->database);

        if($this->conn->connect_error) {
            die("Error, conexión fallida: ". $this->conn->connect_error);
        }
        $this->conn->set_charset('utf8mb4');
    }

    /**
     * Funcion que obtiene la conexion creada
     * @return mysqli
     */
    public function obtenerConexion() {
        return $this->conn;
    }

    /**
     * Cierra la conexión
     * @return void
     */
    public function close() {
        if($this->conn) $this->conn->close();
    }
    }
?>