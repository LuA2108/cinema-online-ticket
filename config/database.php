<?php
class DataBase
{
    private $host = 'localhost';
    private $usuario = 'root';
    private $password = "";
    private $database = "cinema-online-ticket";
    public $conn;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->usuario, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Conexion fallida: " . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf-8');
    }

    public function close() {
        if($this->conn) $this->conn->close();
    }
}
