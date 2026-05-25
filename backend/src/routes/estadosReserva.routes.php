<?php

use App\Controllers\EstadoReservaController;
use App\Service\EstadoReservaService;
use App\Models\EstadoReserva;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/EstadoReserva.php";
require_once __DIR__ . "/../service/EstadoReservaService.php";
require_once __DIR__ . "/../controllers/estadosReservaController.php";

global $respuesta;

// Conexión DB
$conn = (new Database())->obtenerConexion();

// Modelo
$model = new EstadoReserva($conn);

// Service
$service = new EstadoReservaService($model);

// Controller
$controller = new EstadoReservaController($service);

// Request
$method = $_SERVER['REQUEST_METHOD'];
$route = $_GET['route'] ?? '';
$route = trim($route, '/');
$segments = $route ? explode('/', $route) : [];

$resource = $segments[0] ?? null;
$id = $segments[1] ?? null;

if ($resource !== 'estados-reserva') {
    return;
}

$respuesta = true;

try {

    /**
     * GET /estados-reserva
     */
    if ($method === 'GET' && !$id) {
        echo json_encode($controller->index());
        exit;
    }

    /**
     * GET /estados-reserva/1
     */
    if ($method === 'GET' && is_numeric($id)) {
        echo json_encode($controller->mostrarEstadoPorID($id));
        exit;
    }

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}