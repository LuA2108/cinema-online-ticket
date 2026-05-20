<?php
require_once 'config/database.php';

/**
 * Clase Usuario
 * Gestiona operaciones CRUD sobre la tabla usuario
 * Usa una conexion mysqli mediante inyección de dependencias
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
     * Obtiene todos los usuarios de la base de datos
     * @return array Array Lista de usuarios registrados
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
     * Obtiene los datos del usuario según su email
     * @param string $email correo del usuario
     * @return array Array con datos del usuario   
     */
    public function buscarPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = ? ");
        $stmt -> bind_param("s", $email);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    /**
     * Crea un nuevo usuario en la Base de datos.
     * @param int $rol_id rol de usuario ()
     * @param string $nombre
     * @param string $email
     * @param string $contrasena
     * @param string $ciudad
     * @param string $provincia
     * @return bool True si se creo correctamente, False si fallo
     */
    public function crearUsuario($rol_id, $nombre, $email, $contrasena, $ciudad, $provincia)
    {
        $sql = $this->conn->prepare("INSERT INTO usuario(rol_id, nombre, email, contrasena, ciudad, provincia) VALUES (?,?,?,?,?,?)");
        $sql->bind_param("isssss", $rol_id, $nombre, $email, $contrasena, $ciudad, $provincia);

        return $sql->execute();
    }

    /**
     * Actualiza los datos de un usuario existente
     * @param int $rol_id
     * @param string $nombre
     * @param string $email
     * @param string $contrasena
     * @param string $ciudad
     * @param string $provincia
     * @param int $id_usuario
     * @return bool True si se actualizo, False en caso contrario
     */
    public function actualizarUsuario($rol_id, $nombre, $email, $contrasena, $ciudad, $provincia, $id_usuario) {
        $sql = $this->conn->prepare("UPDATE usuario SET rol_id = ?, email= ?, nombre = ?, contrasena = ?, ciudad = ?, provincia = ? WHERE id = ?");
        $sql->bind_param("isssssi", $rol_id, $email, $nombre, $contrasena, $ciudad, $provincia, $id_usuario);    

        return $sql->execute();
    }

    /**
     * Elimina un usuario de la base de datos según su ID
     * @param int $usuario_id ID del usuario
     * @return bool True si se elimino, false al fallar
     */
    public function eliminarUsuario($usuario_id) {
        $sql = $this->conn->prepare("DELETE FROM usuario WHERE id = ?");
        $sql ->bind_param("i", $usuario_id);
        return $sql->execute();
    }
}
