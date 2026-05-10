<?php

require_once __DIR__ . "/../models/usuario.php";

/**
 * Clase que gestiona la autenticacion de usuarios
 */
class authController
{
    private $userModelo;

    /**
     * Contructor de la clase autenticacion de usuarios
     * @param mixed $conn conexion a la BD
     */
    public function __construct($conn)
    {
        // Inicializa el modelo con la conexión recibida
        $this->userModelo = new Usuario($conn);
    }

    /**
     * Metodo que autentica al usuario
     * Verifica que el metodo sea post
     * Obtiene email y contraseña
     * Busca al usuario en la BD
     * Valida la contraseña
     * Guarda los datos de sesion
     * redirige segun el rol
     */
    public function autenticacion() 
    {
        // Solo permite peticiones POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=login");
            exit;
        }

        // Obtiene los datos enviados por el formulario
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

        // Busca el usuario por email
        $usuario = $this->userModelo->buscarPorEmail($email);

        // Verifica si el usuario existe y si la contraseña es correcta
        if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            header("Location: index.php?page=login");
            exit;
        }

        // Guarda datos del usuario en la sesión
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['rol_id'] = $usuario['rol_id'];

        // Redirige según el rol del usuario
        if ($usuario['rol_id'] == 1) {
            header('Location: index.php?page=admin/index');
        } else {
            header('Location: index.php?page=index');
        }
        exit;
    }

    /**
     * Función que cierra la sesion del usuario.
     * Elimina las variables de sesion, destruye sesión y redirige a inicio.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php?page=index");
        exit;
    }
}

