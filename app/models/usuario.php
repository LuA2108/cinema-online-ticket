<?php
require_once 'config/database.php';

/**
 * Clase que gestiona a los usuarios:
 * crear, editar, eliminar y consultar datos
 * Utilizanado una conexion a la BD para ejecutar las consultas sql
 * @author lucero Anay Cahuana
 */
class Usuario
{   
    private $conn;

    /**
     * Constructor de la clase
     * Recibe una conexión a la BD mediante inyección de dependencias
     * @param mysqli $conn Conexión a la BD
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Obtiene todos de usuarios de la BD
     * @return array Lista de usuarios
     */
    public function obtenerUsuarios() {
        $sql = "SELECT * FROM usuarios";
        $resultado = $this->conn->query($sql);
        
        $usuarios = [];
        while($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }
    
    /**
     * Función que devuelve un usuario segun el email
     * @param $email correo del usuario
     * @return array|bool|null array con datos del usuario   
     */
    public function buscarPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ? ");
        $stmt -> bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
        
        return $stmt->get_result()->fetch_assoc();
    }

}
