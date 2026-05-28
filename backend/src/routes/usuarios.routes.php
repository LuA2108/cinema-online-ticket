<?php

use App\Controller\UsuarioController;
use App\Service\UsuarioService;
use App\Models\Usuario;

require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../service/UsuarioService.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL

global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELO
$usuarioModel = new Usuario($conn);

// SERVICE
$usuarioService = new UsuarioService($usuarioModel, $conn);

// CONTROLLER
$controller = new UsuarioController($usuarioService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /usuarios
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /usuarios/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->mostrarUsuarioID($id));
        exit;
    }

    // GET /usuarios/email/test@test.com
    if ($method === 'GET' && $param === 'email') {
        echo json_encode($controller->mostrarUsuarioEmail($segments[2] ?? ''));
        exit;
    }

    // POST /usuarios
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->guardar($body));
        exit;
    }

    // PUT /usuarios/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizar($id, $body));
        exit;
    }

    // DELETE /usuarios/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminar($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de usuarios no válida'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}