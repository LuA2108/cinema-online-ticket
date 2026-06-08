<?php

use App\Controllers\ButacaController;
use App\Service\ButacaService;
use App\Models\Butaca;
use App\Models\Sala;
use App\Models\Funcion;
use App\Models\Programacion;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/butaca.php";
require_once __DIR__ . "/../models/sala.php";
require_once __DIR__ . "/../models/funcion.php";
require_once __DIR__ . "/../models/programacion.php";
require_once __DIR__ . "/../service/butacaService.php";
require_once __DIR__ . "/../controllers/butacaController.php";

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELOS
$butacaModel = new Butaca($conn);
$salaModel = new Sala($conn);
$funcionModel = new Funcion($conn);
$programacionModel = new Programacion($conn);

// SERVICE
$butacaService = new ButacaService($butacaModel, $salaModel, $funcionModel, $programacionModel, $conn);

// CONTROLLER
$controller = new ButacaController($butacaService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents("php://input"), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /butacas/1
    if ($method === 'GET' && $id !== null) {
        echo json_encode(
            $controller->obtenerButacaPorId($id)
        );
        exit;
    }

    // GET /butacas/sala/1
    if ($method === 'GET' && $param === 'sala' && isset($segments[2])) {
        echo json_encode($controller->obtenerButacasPorSala((int)$segments[2]));
        exit;
    }

    // GET /butacas/mapa/1
    if ($method === 'GET' && $param === 'mapa' && isset($segments[2])) {
        echo json_encode($controller->obtenerMapaButacas((int)$segments[2]));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "Ruta de butacas no válida"
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}
