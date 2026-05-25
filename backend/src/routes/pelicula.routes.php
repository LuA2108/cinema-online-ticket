<?php

use App\Controllers\PeliculaController;
use App\Service\PeliculaService;
use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\PeliculaGenero;

require_once __DIR__ . '/../../config/database.php';

// Cargar clases manualmente
require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../models/PeliculaGenero.php';
require_once __DIR__ . '/../service/PeliculaService.php';
require_once __DIR__ . '/../controllers/PeliculaController.php';

header('Content-Type: application/json');

// Permitir peticiones desde JS/frontend
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder preflight de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Crear conexión
$conn = (new Database())->obtenerConexion();

// Crear modelos
$peliculaModel = new Pelicula($conn);
$generoModel = new Genero($conn);
$peliculaGeneroModel = new PeliculaGenero($conn);

// Crear service
$peliculaService = new PeliculaService(
    $peliculaModel,
    $peliculaGeneroModel,
    $generoModel,
    $conn
);

// Crear controller
$controller = new PeliculaController($peliculaService);

// Leer método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Leer JSON enviado desde el frontend
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Leer ruta enviada por .htaccess
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

$segments = $route === '' ? [] : explode('/', $route);

$resource = $segments[0] ?? null;
$param = $segments[1] ?? null;
$action = $segments[2] ?? null;

// También permitimos query params por compatibilidad
$id = is_numeric($param) ? (int) $param : null;

try {

    if ($resource !== 'peliculas') {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Recurso no encontrado'
        ]);
        exit;
    }

    // GET /api/peliculas
    if ($method === 'GET' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->index()
        ]);
        exit;
    }

    // GET /api/peliculas/completas
    if ($method === 'GET' && $param === 'completas') {
        echo json_encode([
            'success' => true,
            'data' => $controller->listarPeliculasCompletas()
        ]);
        exit;
    }

    // GET /api/peliculas/1
    if ($method === 'GET' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->mostrarPelicula($id)
        ]);
        exit;
    }

    // POST /api/peliculas
    if ($method === 'POST' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->guardarPelicula($body)
        ]);
        exit;
    }

    // PUT /api/peliculas/1
    if ($method === 'PUT' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->actualizarPelicula($id, $body)
        ]);
        exit;
    }


    // PATCH /api/peliculas/1/activar
    if ($method === 'PATCH' && $id && $action === 'activar') {
        echo json_encode([
            'success' => true,
            'data' => $controller->activar($id)
        ]);
        exit;
    }

    // PATCH /api/peliculas/1/desactivar
    if ($method === 'PATCH' && $id && $action === 'desactivar') {
        echo json_encode([
            'success' => true,
            'data' => $controller->desactivar($id)
        ]);
        exit;
    }

    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta no encontrada'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
