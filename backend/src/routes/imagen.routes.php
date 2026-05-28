<?php

use App\Controllers\PeliculaImagenController;
use App\Service\PeliculaImagenService;
use App\Models\PeliculaImagen;
use App\Models\Pelicula;

require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/../controllers/peliculaImagenController.php';
require_once __DIR__ . '/../models/PeliculaImagen.php';
require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../service/PeliculaImagenService.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
$conn = (new Database())->obtenerConexion();

// MODELOS
$imagenModel = new PeliculaImagen($conn);
$peliculaModel = new Pelicula($conn);

// SERVICE
$service = new PeliculaImagenService($imagenModel, $peliculaModel);

// CONTROLLER
$controller = new PeliculaImagenController($service);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ======================================
// ROUTES
// ======================================

try {

    // GET /imagenes
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /imagenes/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerImagen($id));
        exit;
    }

    // GET /imagenes/pelicula/1
    if ($method === 'GET' && $param === 'pelicula') {
        $peliculaId = (int)($segments[2] ?? 0);

        echo json_encode($controller->imagenesPorPelicula($peliculaId));
        exit;
    }

    // POST /imagenes
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->crearImagen($body));
        exit;
    }

    // DELETE /imagenes/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarImagen($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de imágenes no válida'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}