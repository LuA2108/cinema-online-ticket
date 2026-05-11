<?php
require_once __DIR__ . '/../models/usuario.php';

/**
 * Controlador para funciones de administrador
 */
class adminController
{
    private $conn;
    private $userModelo;

    /**
     * Constructor del controlador admin
     * @param string $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
        // Inicializa el modelo de usuario con la conexión
        $this->userModelo = new Usuario($this->conn);
    }

    /**
     * Verifica si el usuario es administrador.
     * Si no hay sesión iniciada o o rol de administrador redirige a la página login
     * @return void
     */
    public function authAdmin()
    {
        // Comprueba si no existe sesión o no es admin
        if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] !== 1) {
            // Redirige a login
            header("Location: index.php?page=login");
            exit;
        }
    }

    public function listarUsuarios() {
        // Verifica si es admin
        $this->authAdmin();

        $_SESSION['lista_usuarios'] = $this->userModelo->obtenerUsuarios();
    }
}
