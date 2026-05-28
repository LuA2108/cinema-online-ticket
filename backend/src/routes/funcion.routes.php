<?php

use App\Controllers\FuncionController;
use App\Service\FuncionService;
use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use App\Models\EstadoFuncion;

require_once __DIR__ . "/../../config/database.php";

require_once __DIR__ . "/../controllers/funcionController.php";
require_once __DIR__ . "/../service/funcionService.php";

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
$pelicula = new Pelicula($conn);
$sala = new Sala($conn);
$estadoFuncion = new EstadoFuncion($conn);

// SERVICE
$funcionService = new FuncionService(
    $funcion,
    $pelicula,
    $estadoFuncion,
    $sala
);

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

    // GET /funciones/pelicula/1
    if ($method === 'GET' && $param === 'pelicula' && isset($segments[2])) {
        $pelicula_id = (int) $segments[2];

        echo json_encode($controller->obtenerFuncionesPorPelicula($pelicula_id));
        exit;
    }

    // GET /funciones/estados
    if ($method === 'GET' && $param === 'estados') {
        echo json_encode($controller->obtenerEstados());
        exit;
    }

    // GET /funciones/estado-id/1
    if ($method === 'GET' && $param === 'estado-id' && isset($segments[2])) {
        $estado_id = (int) $segments[2];

        echo json_encode($controller->obtenerEstado($estado_id));
        exit;
    }

    // POST /funciones
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->crearFuncion($body));
        exit;
    }

    // PUT /funciones/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarFuncion($id, $body));
        exit;
    }

    // DELETE /funciones/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarFuncion($id));
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
