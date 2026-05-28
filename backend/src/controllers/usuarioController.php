<?php

namespace App\Controller;

use App\authMiddleware\AuthMiddleWare;
use App\Service\UsuarioService;

/**
 * Controlador de usuarios
 * Gestiona la logica de los usuarios, como intermediario entre el modelo y vista
 */
class UsuarioController
{
    private $usuarioService;

    /**
     * Contructor del controlador de usuarios
     * @param UsuarioService $usuarioService Servicio de usuarios para realizar operaciones relacionadas con usuarios
     * @param mysqli $conn
     */
    public function __construct($usuarioService)
    {
        $this->usuarioService = $usuarioService;    
    }

    public static function perfil() {
        $user = AuthMiddleWare::verificarToken();

        echo json_encode([
            "mensaje" => "Acceso usuario permitido",
            "usuario" => $user
        ]);
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
     * Actualiza un usuario existente
     * @param mixed $id ID del usuario a actualizar
     * @param array $datos Datos actualizados del usuario
     * @return array Resultado de la actualización, incluyendo un mensaje de éxito o error según corresponda
     */
    public function actualizar($id, $datos) {
        return $this->usuarioService->actualizarUsuario($id, $datos);
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
