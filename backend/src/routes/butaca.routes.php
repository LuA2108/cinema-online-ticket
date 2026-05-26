<?php 
use App\Controllers\ButacaController;
use App\Service\ButacaService;
use App\Models\Butaca;
use App\Models\Sala;
use App\Models\Funcion;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/butaca.php";
require_once __DIR__ . "/../models/sala.php";
require_once __DIR__ . "/../models/funcion.php";

require_once __DIR__ . "/../service/butacaService.php";
require_once __DIR__ . "/../controllers/butacaController.php";

global $respuesta;

// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELOS
$butacaModel = new Butaca($conn);
$salaModel = new Sala($conn);
$funcionModel = new Funcion($conn);

// SERVICE
$butacaService = new ButacaService($butacaModel, $salaModel, $funcionModel, $conn);

// CONTROLLER
$controller = new ButacaController($butacaService);

// =========================
// REQUEST
// =========================
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents("php://input"), true) ?? [];

$route = $_GET['route'] ?? '';
$route = trim($route, '/');
$segments = $route ? explode('/', $route) : [];

$resource = $segments[0] ?? null;
$param = $segments[1] ?? null;
$id = is_numeric($param) ? (int)$param : null;

if ($resource !== 'butacas') {
    return;
}

$respuesta = true;

try {

    // GET /butacas/sala/1
    if ($method === 'GET' && $param === 'sala' && isset($segments[2])) {
        $salaId = (int)$segments[2];
        echo json_encode($controller->obtenerButacasPorSala($salaId));
        exit;
    }

    // GET /butacas/funcion/1
    if ($method === 'GET' && $param === 'funcion' && isset($segments[2])) {
        $funcionId = (int)$segments[2];
        echo json_encode($controller->obtenerMapaButacas($funcionId));
        exit;
    }

    // GET /butacas/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerButacaPorId($id));
        exit;
    }

    // POST /butacas/generar
    if ($method === 'POST' && $param === 'generar') {
        echo json_encode($controller->generarButacas($body));
        exit;
    }

    // DELETE /butacas/sala/1
    if ($method === 'DELETE' && $param === 'sala' && isset($segments[2])) {
        $salaId = (int)$segments[2];
        echo json_encode($controller->eliminarButacasSala($salaId));
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

?>