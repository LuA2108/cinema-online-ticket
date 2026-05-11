<?php
// Inicia sesion 
session_start();

// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar base datos
require __DIR__ . '/config/database.php';

//Cargar controlador principal
require __DIR__ . '/app/controllers/pageController.php';

// Dependencias, conexion a la BD 
$db = new Database();
$conn = $db->obtenerConexion();

//Obtener pagina (por defecto index)
$page = $_GET['page'] ?? 'index';

// Controlador principal
$controlador = new PageController($conn);

// Ejecutar
$controlador->cargarPaginas($page);

?>