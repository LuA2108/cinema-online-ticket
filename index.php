<?php
// Inicia sesion 
session_start();

// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar base datos
require __DIR__ . '/config/database.php';

//Cargar controladores
require __DIR__ . '/app/controllers/pageController.php';
require __DIR__ . '/app/controllers/usuarioController.php';

//Cargar Modelos 
require_once __DIR__ . '/app/models/usuario.php';


// Dependencias, conexion a la BD 
$db = new Database();
$conn = $db->obtenerConexion();

//Obtener pagina (por defecto index)
$page = $_GET['page'] ?? 'index';

// Instancia del modelo usuario
$usuario = new Usuario($conn);
$userController = new UserControlador($usuario);

// Instancia del controlador 
$controlador = new pageController($conn);

if($page === 'auth/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    //Si falla, asigna mensaje de error
    if(!$userController->login($email, $contrasena)) {
        $_SESSION['error'] = "Usuario o contraseña incorrecto.";
    }
}

if($page === 'auth/logout') {
    $userController->logout();
}

// El controlador gestiona las paginas
$controlador->mostrarPagina($page);
?>