<?php

//  IMPORTACIÓN DE CLASES NECESARIAS
//Se importan controlador, servicio y modelos utilizados por el módulo de películas.
use App\Controllers\PeliculaController;
use App\Service\PeliculaService;
use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\PeliculaGenero;
use App\Models\PeliculaImagen;

// ======================================
// DEPENDENCIAS
// ======================================


// Cargar clases manualmente
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../models/PeliculaGenero.php';
require_once __DIR__ . "/../models/peliculaImagen.php";
require_once __DIR__ . '/../service/PeliculaService.php';
require_once __DIR__ . '/../controllers/PeliculaController.php';


// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action;

// Crear conexión a base de datos
$conn = (new Database())->obtenerConexion();

// Instanciar modelos
$peliculaModel = new Pelicula($conn);
$generoModel = new Genero($conn);
$peliculaGeneroModel = new PeliculaGenero($conn);
$imagen = new PeliculaImagen($conn);

// Crear servicio con inyección de dependencias
$peliculaService = new PeliculaService(
    $peliculaModel,
    $peliculaGeneroModel,
    $imagen,
    $generoModel,
    $conn
);

// Crear controller
$controller = new PeliculaController($peliculaService);

// DATOS DE LA PETICIÓN

// Leer método HTTP utilizado
$method = $_SERVER['REQUEST_METHOD'];

// Leer JSON enviado desde el frontend
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Convertir el parámetro a ID numérico si es posible, o dejarlo como null
$id = is_numeric($param) ? (int) $param : null;

// ========
// ROUTES
// ========
try {

    // GET /peliculas
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /peliculas/1/completa
    if ($method === 'GET' && $id && $action === 'completa') {
        echo json_encode($controller->obtenerPeliculaCompletaPorId($id));
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
    if ($method === 'PATCH' && $id && $action === 'activar') {
        echo json_encode($controller->activar($id));
        exit;
    }

    // PATCH /peliculas/1/desactivar
    if ($method === 'PATCH' && $id && $action === 'desactivar') {
        echo json_encode($controller->desactivar($id));
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
