<?php

use App\Controllers\EstadoReservaController;
use App\Service\EstadoReservaService;
use App\Models\EstadoReserva;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/EstadoReserva.php";
require_once __DIR__ . "/../service/EstadoReservaService.php";
require_once __DIR__ . "/../controllers/estadosReservaController.php";

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN DB
$conn = (new Database())->obtenerConexion();

// MODELO
$model = new EstadoReserva($conn);

// SERVICE
$service = new EstadoReservaService($model);

// CONTROLLER
$controller = new EstadoReservaController($service);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;


// ROUTES
try {

    // GET /estados-reserva
    if ($method === 'GET' && !$id) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /estados-reserva/1
    if ($method === 'GET' && is_numeric($id)) {
        echo json_encode($controller->mostrarEstadoPorID($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Ruta de estados de reserva no válida"
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}