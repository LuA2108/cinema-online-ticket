<?php

use App\Controllers\ReservaController;
use App\Service\ReservaService;
use App\Models\Reserva;
use App\Models\Funcion;
use App\Models\EstadoReserva;
use App\Models\Usuario;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Funcion.php';
require_once __DIR__ . '/../models/EstadoReserva.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../service/ReservaService.php';
require_once __DIR__ . '/../controllers/ReservaController.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELOS
$reservaModel = new Reserva($conn);
$funcionModel = new Funcion($conn);
$estadoReservaModel = new EstadoReserva($conn);
$usuarioModel = new Usuario($conn);

// SERVICE
$reservaService = new ReservaService(
    $reservaModel,
    $usuarioModel,
    $estadoReservaModel,
    $funcionModel,
    $conn
);

// CONTROLLER
$controller = new ReservaController($reservaService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /reservas
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->listarReservas());
        exit;
    }

    // GET /reservas/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerReservaId($id));
        exit;
    }

    // GET /reservas/usuario/1
    if ($method === 'GET' && $param === 'usuario' && isset($segments[2])) {
        $usuario_id = (int) $segments[2];
        echo json_encode($controller->obtenerPorUsuario($usuario_id));
        exit;
    }

    // GET /reservas/funcion/1
    if ($method === 'GET' && $param === 'funcion' && isset($segments[2])) {
        $funcion_id = (int) $segments[2];
        echo json_encode($controller->obtenerPorFuncion($funcion_id));
        exit;
    }

    // POST /reservas
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->crearReserva($body));
        exit;
    }

    // PUT /reservas/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarReserva($id, $body));
        exit;
    }

    // PATCH /reservas/1/estado
    if ($method === 'PATCH' && $id && $param === 'estado') {
        echo json_encode($controller->cambiarEstadoReserva($id, (int) $body['estado_id']));
        exit;
    }

    // DELETE /reservas/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarReserva($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Ruta de reservas no válida"
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}