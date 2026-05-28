<?php

use App\Controllers\PeliculaController;
use App\Service\PeliculaService;
use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\PeliculaGenero;

// DEPENDENCIAS
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../models/PeliculaGenero.php';
require_once __DIR__ . '/../service/PeliculaService.php';
require_once __DIR__ . '/../controllers/PeliculaController.php';

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
$conn = (new Database())->obtenerConexion();

$peliculaModel = new Pelicula($conn);
$generoModel = new Genero($conn);
$peliculaGeneroModel = new PeliculaGenero($conn);

$peliculaService = new PeliculaService(
    $peliculaModel,
    $peliculaGeneroModel,
    $generoModel,
    $conn
);

$controller = new PeliculaController($peliculaService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /peliculas
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /peliculas/completas
    if ($method === 'GET' && $param === 'completas') {
        echo json_encode($controller->listarPeliculasCompletas());
        exit;
    }

    // GET /peliculas/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->mostrarPelicula($id));
        exit;
    }

    // POST /peliculas
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->guardarPelicula($body));
        exit;
    }

    // PUT /peliculas/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarPelicula($id, $body));
        exit;
    }

    // PATCH /peliculas/1/activar
    if ($method === 'PATCH' && $id && $action === 'activar') 
    {
        echo json_encode(
            $controller->activar($id)
        );

        exit;
    }

    // PATCH /peliculas/1/desactivar
    if ($method === 'PATCH' && $id && $action === 'desactivar') 
    {
        echo json_encode($controller->desactivar($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de películas no válida'
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}