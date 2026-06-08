<?php

use App\Models\Sala;
use App\Models\Butaca;
use App\Service\SalaService;
use App\Controllers\SalaController;


require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/butaca.php';
require_once __DIR__ . '/../models/Sala.php';
require_once __DIR__ . '/../service/SalaService.php';
require_once __DIR__ . '/../controllers/SalaController.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
$conn = (new Database())->obtenerConexion();

// MODELO
$salaModel = new Sala($conn);
$butacaModel = new Butaca($conn);

// SERVICE
$salaService = new SalaService($salaModel, $butacaModel, $conn);

// CONTROLLER
$salaController = new SalaController($salaService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /salas
    if ($method === 'GET' && !$param) {
        echo json_encode($salaController->listarSalas());
        exit;
    }

    // GET /salas/1
    if ($method === 'GET' && $id) {
        echo json_encode($salaController->obtenerSala($id));
        exit;
    }

    // GET /salas/estado/1
    if ($method === 'GET' && $param === 'estado') {
        $estado = isset($segments[2]) ? (bool) $segments[2] : true;

        echo json_encode($salaController->listarPorEstado($estado));
        exit;
    }

    // POST /salas
    if ($method === 'POST' && !$param) {
        $numero = $body['numero'] ?? null;
        $filas = $body['filas'] ?? null;
        $butacasPorFila = $body['butacasPorFila'] ?? null;

        echo json_encode($salaController->crearSala($numero, $filas, $butacasPorFila));
        exit;
    }

    // PUT /salas/1/estado
    if ($method === 'PUT' && $id && isset($segments[2]) && $segments[2] === 'estado') {
        $estado = $body['estado'] ?? null;
        echo json_encode($salaController->cambiarEstadoSala($id, $estado));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de salas no válida'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

