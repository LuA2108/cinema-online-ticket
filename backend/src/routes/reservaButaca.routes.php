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

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN
$conn = (new Database())->obtenerConexion();

// MODELOS
$reservaButacaModel = new ReservaButaca($conn);
$butacaModel = new Butaca($conn);
$funcionModel = new Funcion($conn);
$salaModel = new Sala($conn);

// SERVICE
$reservaButacaService = new ReservaButacaService(
    $reservaButacaModel,
    $butacaModel,
    $funcionModel,
    $salaModel
);

// CONTROLLER
$controller = new ReservaButacaController($reservaButacaService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents("php://input"), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // POST /reserva-butacas
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->agregarButacaAReserva($body));
        exit;
    }

    // DELETE /reserva-butacas
    if ($method === 'DELETE' && !$param) {
        echo json_encode($controller->eliminarButacaDeReserva($body));
        exit;
    }

    // GET /reserva-butacas/reserva/1
    if ($method === 'GET' && $param === 'reserva' && isset($segments[2])) {
        $reserva_id = (int) $segments[2];

        echo json_encode($controller->obtenerPorReserva($reserva_id));
        exit;
    }

    // GET /reserva-butacas/funcion/1
    if ($method === 'GET' && $param === 'funcion' && isset($segments[2])) {
        $funcion_id = (int) $segments[2];

        echo json_encode($controller->obtenerPorFuncion($funcion_id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Ruta de reserva-butacas no válida"
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}
