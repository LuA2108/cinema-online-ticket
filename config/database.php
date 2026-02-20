<?php

/**
 * Clase Database
 * 
 * Maneja la conexión a la base de datos MySQL usando MySQLi.
 * Proporciona métodos para abrir y cerrar la conexión.
 * Se puede extender para incluir transacciones (commit, rollback) y consultas preparadas.
 *
 * @package CineToon
 * @author TuNombre
 * @version 1.0
 */
class DataBase
{
    /** Host de la base de datos */
    private $host = 'localhost'; 

    /** Usuario de la base de datos */
    private $usuario = 'root'; 

    /** Contraseña de la base de datos */
    private $password = ""; 

    /** Nombre de la base de datos */
    private $database = "cinema-online-ticket"; 

    /** Objeto de conexión MySQLi */
    public $conn; 

    /**
     * Constructor de la clase
     * Inicializa la conexión a la base de datos automáticamente
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Conecta a la base de datos
     * @return void
     * @throws Exception si la conexión falla
     */
    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->usuario, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Conexion fallida: " . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf-8');
    }

    /**
     * Ciera la conexion a la BD
     * @return void
     */
    public function close() {
        if($this->conn) $this->conn->close();
    }
}
