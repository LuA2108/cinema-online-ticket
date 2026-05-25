<?php

use App\Models\Sala;
use App\Service\SalaService;
use App\Controllers\SalaController;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Sala.php';
require_once __DIR__ . '/../service/SalaService.php';
require_once __DIR__ . '/../controllers/SalaController.php';

$conn = (new Database())->obtenerConexion();

// Instancia del modelo de Sala con la conexión a la base de datos
$salaModel = new Sala($conn);
// Instancia del servicio de Sala con el modelo inyectado
$salaService = new SalaService($salaModel);
// Instancia del controlador de Sala con el servicio inyectado
$salaController = new SalaController($salaService);

global $respuesta; // Variable global para indicar si la ruta fue manejada por este módulo

// LECTURA DE DATOS DE LA PETICIÓN ///////////////////////
// Leer método HTTP utilizado
$method = $_SERVER['REQUEST_METHOD'];

// Leer JSON enviado desde el frontend
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Obtener route enviada por Apache Rewrite
$route = $_GET['route'] ?? '';
$route = trim($route, '/'); // Eliminar barras sobrantes

// Convertir la ruta en segmentos: peliculas/1/activar 
// Ejemplo: "peliculas/1/activar" => ["peliculas", "1", "activar"]
$segments = $route === '' ? [] : explode('/', $route);

// Recurso principal
$resource = $segments[0] ?? null; // Parámetro adicional (ID o acción)

// Parámetro principal (normalmente ID) o acción (activar/desactivar)
$param = $segments[1] ?? null;

// Acción adicional para rutas como: /peliculas/1/activar
$action = $segments[2] ?? null;

// Convertir el parámetro a ID numérico si es posible, o dejarlo como null
$id = is_numeric($param) ? (int) $param : null;

/* SI NO ES ESTE MÓDULO, SALIR */
if ($resource !== 'salas') {
    return;
}

$respuesta = true; // Variable para indicar si este módulo manejó la ruta actual

try {

    // GET /api/salas - Listar todas las salas
    if ($method === 'GET' && !$param) {
        echo json_encode(
            $salaController->listarSalas()
        );
        exit;
    }

    // GET /api/salas/1 - Obtener sala por ID
    if ($method === 'GET' && $id) {
        echo json_encode(
            $salaController->obtenerSala($id)
        );
    exit;
    }

    // GET /api/salas/1 - Obtener sala por ID
    if ($method === 'GET' && $param === 'activas') {
        $estado = isset($segments[2]) ? (bool)$segments[2] : true;

        echo json_encode(
            $salaController->listarActivas($estado)
        );
        exit;
    }

    // POST /api/salas - Crear nueva sala
    if ($method === 'POST' && !$param) {
        $numero = $body['numero'] ?? null;
        $capacidad = $body['capacidad'] ?? null;

        echo json_encode(
            $salaController->crearSala($numero, $capacidad)
        );
        exit;
    }

    // PUT /api/salas/1 - Actualizar sala existente
    if ($method === 'PUT' && $id) {
        echo json_encode(
            $salaController->actualizarSala($id, $body['numero'] ?? null, $body['capacidad'] ?? null, $body['activa'] ?? null)
        );
    exit;
    }

    // DELETE /api/salas/1 - Desactivar sala existente
    if ($method === 'DELETE' && $id) {
        echo json_encode(
            $salaController->desactivarSala($id)
        );
        exit;
    }
} catch (Exception $e) {

    // Manejo global de errores con código 500 y mensaje de error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
