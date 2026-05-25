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

global $respuesta;

$conn = (new Database())->obtenerConexion();

// MODELOS
$imagenModel = new PeliculaImagen($conn);
$peliculaModel = new Pelicula($conn);

// SERVICE
$service = new PeliculaImagenService($imagenModel, $peliculaModel);

// CONTROLLER
$controller = new PeliculaImagenController($service);


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

// Si el recurso no es "imagenes", sale del módulo y permite que otro módulo lo maneje
if ($resource !== 'imagenes') {
    return;
}

$respuesta = true; // este route de imagenes se encargará de manejar la solicitud actual

/********** RUTAS **********/
try {

    // GET /imagenes
    if ($method === 'GET' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->index()
        ]);
        exit;
    }

    // GET /imagenes/1
    if ($method === 'GET' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->obtenerImagen($id)
        ]);
        exit;
    }

    // GET /imagenes/pelicula/1
    if ($method === 'GET' && $param === 'pelicula') {

        $peliculaId = (int)($segments[2] ?? 0);

        echo json_encode([
            'success' => true,
            'data' => $controller->imagenesPorPelicula($peliculaId)
        ]);
        exit;
    }

    // POST /imagenes
    if ($method === 'POST' && !$param) {

        $body = json_decode(file_get_contents("php://input"), true);

        echo json_encode([
            'success' => true,
            'data' => $controller->crearImagen($body)
        ]);
        exit;
    }

    // DELETE /imagenes/1
    if ($method === 'DELETE' && $id) {

        echo json_encode([
            'success' => true,
            'data' => $controller->eliminarImagen($id)
        ]);
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
