<?php
require_once __DIR__ . "/authController.php";
require_once __DIR__ . "/adminController.php";

/**
 * Controlador principal - Router + Layouts
 * Maneja TODAS las rutas de la aplicación
 */
class PageController 
{
    private $conn;              // Conexión BD
    private $authController;    // Controlador autenticación
    private $adminController;   // Controlador admin

    /**
     * Constructor - Inicializa las dependencias
     * @param mixed $conn Conexion a la base de datos
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->authController = new authController($this->conn);
        $this->adminController = new adminController($this->conn);
    }

    /**
     * Metodo principal - Carga cualquier página
     * @param string $page Ruta (?page=index)
     * @return void
     */
    public function cargarPaginas($page)
    {

        // 1. FORMULARIOS POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->manejarPost($page);
            return;
        }

        // 2. PROTEGER ADMIN
        // Si intenta acceder admin/ sin ser admin → login
        if (strpos($page, 'admin/') === 0 && !$this->esAdmin()) {
            header('Location: index.php?page=login');
            exit;
        }

        // 3. LAYOUT + CONTENIDO AUTOMÁTICO
        $layout = $this->obtenerLayout($page); // main.php o admin.php
        $content = $this->obtenerVista($page); // Página específica

        // Variables para layout
        $GLOBALS['content'] = $content;

        // Carga layout que incluye $content
        require $layout;
    }

    /**
     * Maneja todos los formularios post
     * @param string $page ruta
     * @return void
     */
    private function manejarPost($page)
    {
        if ($page === 'auth/login') {
            $this->authController->autenticacion(); // Login form
        } elseif ($page === 'auth/logout') {
            $this->authController->logout(); // Logout link
        }

        // ✅ DEBUG: Verifica datos
        if ($page === 'panel_usuarios' || strpos($page, 'admin/') === 0) {
            error_log("DEBUG pageController: Cargando usuarios para $page");
            $this->adminController->listarUsuarios();
        }
        // aqui se agregará más

    }

    /**
     * Comprueba si el usuario es admin por el id del rol
     * @return bool
     */
    private function esAdmin()
    {
        return isset($_SESSION['user_id']) && $_SESSION['rol_id'] == 1;
    }

    private function obtenerLayout($page)
    {
        if ($this->esAdmin() && strpos($page, 'admin/') === 0) {
            return __DIR__ . '/../views/layout/admin.php';
        }
        return __DIR__ . '/../views/layout/main.php';
    }

    private function obtenerVista($page)
    {
        if ($page === 'admin/usuarios/index') {
            $this->adminController->listarUsuarios();
        }

        // AUTOMÁTICO: admin/usuarios --> views/admin/usuarios.php
        $ruta = str_replace('/', '/', $page);
        $archivo = __DIR__ . "/../views/{$ruta}.php";

        if (file_exists($archivo)) {
            return $archivo;
        }

        // TUS RUTAS EXISTENTES
        $rutas = [
            'index' => __DIR__ . '/../views/public/index.php',
            'login' => __DIR__ . '/../views/auth/login.php',
            'perfil' => __DIR__ . '/../views/user/perfil.php',
            'editar' => __DIR__ . '/../views/user/editar.php',
            'panel_usuarios' => __DIR__ . '/../views/admin/usuarios/index.php',
        ];

        return $rutas[$page] ?? __DIR__ . '/../views/404.php';
    }
}
