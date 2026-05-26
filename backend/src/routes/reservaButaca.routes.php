<?php

use App\Service\ReservaButacaService;
use App\Controllers\ReservaButacaController;
use App\Models\ReservaButaca;
use App\Models\Butaca;
use App\Models\Funcion;
use App\Models\Sala;

require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/../models/ReservaButaca.php';
require_once __DIR__ . '/../models/Butaca.php';
require_once __DIR__ . '/../models/Funcion.php';
require_once __DIR__ . '/../models/Sala.php';

require_once __DIR__ . '/../service/ReservaButacaService.php';
require_once __DIR__ . '/../controllers/ReservaButacaController.php';

global $respuesta;

// CONEXIÓN
$conn = (new Database())->obtenerConexion();

// MODELOS
$reservaButacaModel = new ReservaButaca($conn);
$butacaModel = new Butaca($conn);
$funcionModel = new Funcion($conn);
$salaModel = new Sala($conn);

// SERVICE
$reservaButacaService = new ReservaButacaService($reservaButacaModel, $butacaModel, $funcionModel, $salaModel);

// CONTROLLER
$controller = new ReservaButacaController($reservaButacaService);

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

if ($resource !== 'reserva-butacas') {
    return;
}

$respuesta = true;

try {

    // =========================
    // POST: agregar butaca a reserva
    // POST /reserva-butacas
    // =========================
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->agregarButacaAReserva($body));
        exit;
    }

    // =========================
    // DELETE: eliminar butaca de reserva
    // DELETE /reserva-butacas
    // body: reserva_id, funcion_id, butaca_id
    // =========================
    if ($method === 'DELETE' && !$param) {
        echo json_encode($controller->eliminarButacaDeReserva($body));
        exit;
    }

    // =========================
    // GET: butacas por reserva
    // GET /reserva-butacas/reserva/1
    // =========================
    if ($method === 'GET' && $param === 'reserva' && isset($segments[2])) {
        $reserva_id = (int)$segments[2];
        echo json_encode($controller->obtenerPorReserva($reserva_id));
        exit;
    }

    // =========================
    // GET: butacas por función
    // GET /reserva-butacas/funcion/1
    // =========================
    if ($method === 'GET' && $param === 'funcion' && isset($segments[2])) {
        $funcion_id = (int)$segments[2];
        echo json_encode($controller->obtenerPorFuncion($funcion_id));
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