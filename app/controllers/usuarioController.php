<?php
//Importa modelo usuario
require_once __DIR__ . "/../models/usuario.php";

/**
 * Controlador de usuarios
 * Gestiona la logica de los usuarios, como intermediario entre el modelo y vista
 */
class UserControlador
{

    private $usuario;

    /**
     * Constructor del controlador de usuarios
     * @param $usuario Objeto del modelo usuario 
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Obtiene la lista de todos los usuarios
     * @return array Lista de usuarios
     */
    public function listarUsuarios()
    {
        return $this->usuario->obtenerUsuarios();
    }

    /**
     * Gestiona el inicio de sesion del usuario.
     * 
     * Verifica si el usuario existe y si la contraseña es correcta. En caso
     * valido redirige segun el rol 
     * @param string $email correo electronico del usuario
     * @param string $contrasena contraseña introducida 
     * @return bool devuelve True si es correcto
     */
    public function login($email, $contrasena)
    {
        $usuario = $this->usuario->buscarPorEmail($email);

        // Comprobar existencia de usuario
        if (!$usuario) {
            return false;
        }

        // Conprobar contraseña
        if (!password_verify($contrasena, $usuario['contrasena'])) {
            return false;
        }

        // Creacion de sesion al ser correcto el login
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['rol_id'] = $usuario['rol_id'];
        $_SESSION['email'] = $usuario['email'];

        // Redirección al panel de Admin 
        if ($usuario['rol_id'] == 1) {
            header("Location: index.php?page=admin/index");
            exit;
        }

        header("Location: index.php?page=index");
        exit;

        return true;
    }


    public function logout()
    {
        session_unset();
        session_destroy();

        header("Location: index.php?page=index");
        exit;
    }
}
