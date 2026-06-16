<?php

namespace App\Models;

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
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtener todos los usuarios (SIN contraseña)
     * @return array Lista de usuarios
     */
    public function obtenerUsuarios()
    {
        $sql = "SELECT id, rol_id, nombre, email, ciudad, provincia, create_time FROM usuario";
        $resultado = $this->conn->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los datos del usuario según su ID
     * @param string $id ID usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function buscarPorID($id)
    {
        $stmt = $this->conn->prepare("SELECT id, rol_id, nombre, email, ciudad, provincia, create_time FROM usuario WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Obtiene los datos del usuario según su email
     * @param string $email correo del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function buscarPorEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT id, rol_id, nombre, email, contrasena FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
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
        $contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
        $sql = $this->conn->prepare("INSERT INTO usuario(rol_id, nombre, email, contrasena, ciudad, provincia) VALUES (?,?,?,?,?,?)");
        $sql->bind_param("isssss", $rol_id, $nombre, $email, $contrasena, $ciudad, $provincia);

        if ($sql->execute()) {
            return $this->conn->insert_id;
        }

        return -1;
    }

    /**
     * Actualiza los datos de un usuario existente
     * @param string $nombre
     * @param string $email
     * @param string $ciudad
     * @param string $provincia
     * @param int $id_usuario
     * @return bool True si se actualizo, False en caso contrario
     */
    public function actualizarUsuario($nombre, $email, $ciudad, $provincia, $id_usuario)
    {
        $sql = $this->conn->prepare("UPDATE usuario SET nombre = ?, email = ?, ciudad = ?, provincia = ? WHERE id = ?");
        $sql->bind_param("ssssi", $nombre, $email, $ciudad, $provincia, $id_usuario);
        return $sql->execute();
    }

    /**
     * Actualiza solo contraseña
     * @param mixed $id_usuario
     * @param mixed $nuevaContrasena
     */
    public function actualizarContrasena($id_usuario, $nuevaContrasena)
    {
        $hash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

        $sql = $this->conn->prepare("
            UPDATE usuario
            SET contrasena = ?
            WHERE id = ?
        ");

        $sql->bind_param("si", $hash, $id_usuario);

        return $sql->execute();
    }

    /**
     * Elimina un usuario de la base de datos según su ID
     * @param int $usuario_id ID del usuario
     * @return bool True si se elimino, false al fallar
     */
    public function eliminarUsuario($usuario_id)
    {
        $sql = $this->conn->prepare("DELETE FROM usuario WHERE id = ?");
        $sql->bind_param("i", $usuario_id);
        return $sql->execute();
    }
}
