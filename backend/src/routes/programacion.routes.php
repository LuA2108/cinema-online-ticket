<?php

use App\Controllers\ProgramacionController;
use App\Service\ProgramacionService;
use App\Models\Programacion;
use App\Models\Pelicula;
use App\Models\Funcion;
use App\Models\Sala;

require_once __DIR__ . "/../../config/database.php";

require_once __DIR__ . "/../controllers/programacionController.php";
require_once __DIR__ . "/../service/programacionService.php";
require_once __DIR__ . "/../models/programacion.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/funcion.php";
require_once __DIR__ . "/../models/sala.php";

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS

// Obtener conexion
$conn = (new Database())->obtenerConexion();

// MODELOS
$programacion = new Programacion($conn);
$pelicula = new Pelicula($conn);
$funcion = new Funcion($conn);
$sala = new Sala($conn);

// SERVICE
$programacionService = new ProgramacionService($programacion, $pelicula, $funcion, $sala, $conn);

// CONTROLLER
$controller = new ProgramacionController($programacionService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /programaciones
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->listarProgramaciones());
        exit;
    }

    // GET /programaciones/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerProgramacion($id));
        exit;
    }

    // GET /programaciones/pelicula/1
    if ($method === 'GET' && $param === 'pelicula' && isset($segments[2])) {
        $peliculaId = (int) $segments[2];

        echo json_encode($controller->obtenerProgramacionesPorPelicula($peliculaId));
        exit;
    }

    // POST /programaciones
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->crearProgramacion($body));
        exit;
    }

    // PUT /programaciones/1/estado
    if ($method === 'PUT' && $id && isset($segments[2]) && $segments[2] === 'estado') {
        $estado = $body['estado'] ?? null;

        echo json_encode($controller->cambiarEstado($id, $estado));
        exit;
    }

    // PUT /programaciones/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarProgramacion($id, $body));
        exit;
    }

    // DELETE /programaciones/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarProgramacion($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Ruta de programaciones no válida']);
} catch (Exception $e) {

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
