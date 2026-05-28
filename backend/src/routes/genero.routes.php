<?php

use App\Controllers\GeneroController;
use App\Service\GeneroService;
use App\Models\Genero;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../service/GeneroService.php';
require_once __DIR__ . '/../controllers/GeneroController.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
$conn = (new Database())->obtenerConexion();

// MODELO
$generoModel = new Genero($conn);

// SERVICE
$generoService = new GeneroService($generoModel);

// CONTROLLER
$controller = new GeneroController($generoService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ======================================
// ROUTES
// ======================================

try {

    // GET /generos
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /generos/completas
    if ($method === 'GET' && $param === 'completas') {
        echo json_encode($controller->index());
        exit;
    }

    // GET /generos/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->mostrarGenero($id));
        exit;
    }

    // POST /generos
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->crearGenero($body));
        exit;
    }

    // PUT /generos/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarGenero($id, $body));
        exit;
    }

    // DELETE /generos/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarGenero($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de géneros no válida'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}