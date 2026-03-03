<?php
// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar el autoload
require __DIR__ . '/app/controllers/MainController.php';

// Determinar qué controlador y acción usar
$controller = new MainController();
$controller->index();