<?php

namespace App\Service;

use App\Models\Usuario;

class UsuarioService
{
    private $conn;
    private $userModel;

    /**
     * contructor de la clase UsuarioService
     * @param Usuario $userModel Modelo de usuario para realizar operaciones relacionadas con usuarios
     * @param mysqli $conn Conexión a la base de datos para inicializar el modelo de usuario
     */
    public function __construct($userModel, $conn)
    {
        $this->conn = $conn;
        $this->userModel = $userModel;
    }

    /**
     * Obtiene una lista de todos los usuarios
     * @return array Resultado de la consulta, incluyendo un mensaje de éxito o error según corresponda
     */
    public function listarUsuarios()
    {
        return ["success" => true, "datos" => $this->userModel->obtenerUsuarios(), "error" => null];
    }

    /**
     * Obtiene un usuario por su email
     * @param mixed $email Correo electrónico del usuario a buscar
     * @return array Resultado de la consulta, incluyendo un mensaje de éxito o error según corresponda
     */
    public function obtenerUsuarioEmail($email)
    {
        // Limpiar espacios y normalizar
        $email = strtolower(trim($email));
        return ["success" => true, "datos" => $this->userModel->buscarPorEmail($email), "error" => null];
    }

    /**
     * Obtiene un usuario por su ID
     * @param int $id ID del usuario a buscar
     * @return array Resultado de la consulta, incluyendo un mensaje de éxito o error según corresponda
     */
    public function obtenerUsuarioPorID($id)
    {
        if ($id <= 0 || !is_numeric($id)) {
            return ["success" => false, "datos" => null, "error" => "ID de usuario inválido"];
        }
        return ["success" => true, "datos" => $this->userModel->buscarPorID($id), "error" => null];
    }

    /**
     * Crea un nuevo usuario
     * @param array $datos Datos del usuario (nombre, email, contraseña, rol_id, ciudad, provincia)
     * @return array Resultado de la creación, incluyendo un mensaje de éxito o error según corresponda 
     */
    public function crearUsuario($datos)
    {
        // Comprueba que no sean valores vacíos
        if (empty($datos['email']) || empty($datos['nombre']) || empty($datos['contrasena'])) {
            return ["success" => false, "datos" => null, "error" => "Faltan datos obligatorios"];
        }

        $email = strtolower(trim($datos['email']));
        $usuarioExistente = $this->userModel->buscarPorEmail($email);

        if ($usuarioExistente) {
            return ["success" => false, "datos" => null, "error" => "El email ya está registrado"];
        }

        $usuarioId = $this->userModel->crearUsuario($datos['rol_id'], $datos['nombre'], $email, $datos['contrasena'], $datos['ciudad'], $datos['provincia']);

        if ($usuarioId === -1) {
            return ["success" => false, "datos" => null, "error" => "No se pudo crear el usuario"];
        }

        return ["success" => true, "datos" => $usuarioId, "error" => null];
    }

    /**
     * Actualiza un usuario existente
     * @param int $id ID del usuario a actualizar
     * @param array $datos Nuevos datos del usuario (nombre, email, rol_id, ciudad, provincia)
     * @return array Resultado de la actualización, incluyendo un mensaje de éxito o error según corresponda
     */
    public function actualizarUsuario($id, $datos)
    {
        $usuario = $this->userModel->buscarPorID($id);

        if (!$usuario) {
            return ["success" => false, "datos" => null, "error" => "Usuario no encontrado"];
        }

        // Si se proporciona un nuevo email, verifica que no esté registrado por otro usuario
        if (!empty($datos['email'])) {
            $email = strtolower(trim($datos['email']));
            $usuarioExistente = $this->userModel->buscarPorEmail($email);

            if ($usuarioExistente && $usuarioExistente['id'] != $id) {
                return ["success" => false, "datos" => null, "error" => "El email ya está registrado por otro usuario"];
            }
        }

        $resultado = $this->userModel->actualizarUsuario($datos['rol_id'], $datos['nombre'], $datos['email'], $datos['ciudad'], $datos['provincia'], $id);

        if (!$resultado) {
            return ["success" => false, "datos" => null, "error" => "No se pudo actualizar el usuario"];
        }

        return ["success" => true, "datos" => null, "error" => null];
    }

    /**
     * Borra un usuario existente
     * @param mixed $id ID usuario
     * @return array Resultado de la eliminación, incluyendo un mensaje de éxito o error según corresponda
     */
    public function eliminarUsuario($id)
    {
        $usuario = $this->userModel->buscarPorID($id);

        if (!$usuario) {
            return ["success" => false, "datos" => null, "error" => "Usuario no encontrado"];
        }

        $resultado = $this->userModel->eliminarUsuario($id);

        if (!$resultado) {
            return ["success" => false, "datos" => null, "error" => "No se pudo eliminar el usuario"];
        }

        return ["success" => true, "datos" => null, "error" => null];
    }
}
