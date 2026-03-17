<?php
// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar el controlador
require __DIR__ . '/app/controllers/pageController.php';

$page = $_GET['page'] ?? 'index';

// Crear instancia del controlador
$controller = new PageController();

// llamar al método principal
$controller->mostrarPaginas($page);