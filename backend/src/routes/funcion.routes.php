<?php

use App\Controllers\FuncionController;
use App\Service\FuncionService;
use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use App\Models\EstadoFuncion;
use App\Models\Programacion;

require_once __DIR__ . "/../../config/database.php";

require_once __DIR__ . "/../controllers/funcionController.php";
require_once __DIR__ . "/../service/funcionService.php";
require_once __DIR__ . "/../models/programacion.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/funcion.php";
require_once __DIR__ . "/../models/sala.php";
require_once __DIR__ . "/../models/estadoFuncion.php";

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELOS
$funcion = new Funcion($conn);
$estadoFuncion = new EstadoFuncion($conn);
$programacion = new Programacion($conn);

// SERVICE
$funcionService = new FuncionService($funcion, $estadoFuncion, $programacion);

// CONTROLLER
$controller = new FuncionController($funcionService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /funciones
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->listarFunciones());
        exit;
    }

    // GET /funciones/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerFuncionId($id));
        exit;
    }

    // GET /funciones/estado/1
    if ($method === 'GET' && $param === 'estado' && isset($segments[2])) {
        $estado_id = (int) $segments[2];

        echo json_encode($controller->obtenerFuncionesPorEstado($estado_id));
        exit;
    }


    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de funciones no válida'
    ]);
} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
