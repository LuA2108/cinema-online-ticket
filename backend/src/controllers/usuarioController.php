<?php

namespace App\Controller;
use App\Service\UsuarioService;

/**
 * Controlador de usuarios
 * Gestiona la logica de los usuarios, como intermediario entre el modelo y vista
 */
class UsuarioController
{
    private $conn;
    private $usuarioService;

    /**
     * Contructor del controlador de usuarios
     * @param mysqli $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->usuarioService = new UsuarioService($conn);
    }

    /**
     * Devuelve una lista de todos los usuarios
     * @return array Resultado de la consulta, incluyendo un mensaje de éxito o error según corresponda
     */
    public function index()
    {
        return $this->usuarioService->listarUsuarios();

    }

    /**
     * Obtiene un usuario por su ID
     * @param mixed $id ID del usuario a mostrar
     * @return array Resultado de la consulta, incluyendo un mensaje de éxito o error según corresponda
     */
    public function mostrarUsuarioID($id)
    {
        return $this->usuarioService->obtenerUsuarioPorID($id);

    }

    /**
     * Obtiene un usuario por su email
     * @param mixed $email Email del usuario a mostrar
     * @return array Resultado de la consulta, incluyendo un mensaje de éxito o error según corresponda
     */
    public function mostrarUsuarioEmail($email)
    {
        return $this->usuarioService->obtenerUsuarioEmail($email);
    }
    

    /**
     * Crea un nuevo usuario
     * @param array $datos Datos del usuario a crear
     * @return array Resultado de la creación, incluyendo un mensaje de éxito o error según corresponda
     */
    public function guardar($datos) {
        return $this->usuarioService->crearUsuario($datos);
    }

    /**
     * Elimina un usuario existente
     * @param mixed $id ID del usuario a eliminar
     * @return array Resultado de la eliminación, incluyendo un mensaje de éxito o error según corresponda
     */
    public function eliminar($id) {
        return $this->usuarioService->eliminarUsuario($id);
    }
}
