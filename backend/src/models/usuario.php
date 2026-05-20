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
        $sql = "SELECT * FROM usuario";
        $resultado = $this->conn->query($sql);
        
        $usuarios = [];
        while($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }
    
    /**
     * Función que devuelve un usuario segun el email
     * @param string $email correo del usuario
     * @return array|bool|null array con datos del usuario   
     */
    public function buscarPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = ? ");
        $stmt -> bind_param("s", $email);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    /**
     * Función que inserta un usuario a la Base de datos.
     * @param int $rol_id rol de usuario ()
     * @param string $nombre
     * @param string $email
     * @param string $contrasena
     * @param string $ciudad
     * @param string $provincia
     * @return bool
     */
    public function crearUsuario($rol_id, $nombre, $email, $contrasena, $ciudad, $provincia)
    {
        $sql = $this->conn->prepare("INSERT INTO usuario(rol_id, nombre, email, contrasena, ciudad, privincia) VALUES (?,?,?,?,?,?)");
        $sql->bind_param("isssss", $rol_id, $nombre, $email, $contrasena, $ciudad, $provincia);

        return $sql->execute();
    }

    public function actualizarUsuario() {

    }
    public function eliminarUsuario() {

    }
}
