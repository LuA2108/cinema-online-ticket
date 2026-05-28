```php id="2q8e4l"
<?php

use App\Models\Sala;
use App\Service\SalaService;
use App\Controllers\SalaController;

require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/../models/Sala.php';
require_once __DIR__ . '/../service/SalaService.php';
require_once __DIR__ . '/../controllers/SalaController.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
$conn = (new Database())->obtenerConexion();

// MODELO
$salaModel = new Sala($conn);

// SERVICE
$salaService = new SalaService($salaModel);

// CONTROLLER
$salaController = new SalaController($salaService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /salas
    if ($method === 'GET' && !$param) {
        echo json_encode($salaController->listarSalas());
        exit;
    }

    // GET /salas/1
    if ($method === 'GET' && $id) {
        echo json_encode($salaController->obtenerSala($id));
        exit;
    }

    // GET /salas/activas
    if ($method === 'GET' && $param === 'activas') {
        $estado = isset($segments[2]) ? (bool) $segments[2] : true;

        echo json_encode($salaController->listarActivas($estado));
        exit;
    }

    // POST /salas
    if ($method === 'POST' && !$param) {
        $numero = $body['numero'] ?? null;
        $capacidad = $body['capacidad'] ?? null;

        echo json_encode($salaController->crearSala($numero, $capacidad));
        exit;
    }

    // PUT /salas/1
    if ($method === 'PUT' && $id) {
        echo json_encode($salaController->actualizarSala(
            $id,
            $body['numero'] ?? null,
            $body['capacidad'] ?? null,
            $body['activa'] ?? null
        ));

        exit;
    }

    // DELETE /salas/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($salaController->desactivarSala($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de salas no válida'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

