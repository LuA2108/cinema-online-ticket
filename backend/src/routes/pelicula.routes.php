<?php

//  IMPORTACIÓN DE CLASES NECESARIAS
//Se importan controlador, servicio y modelos utilizados por el módulo de películas.
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

global $respuesta; // Variable global para indicar si la ruta fue manejada por este módulo

// Crear conexión a base de datos
$conn = (new Database())->obtenerConexion();

// Instanciar modelos
$peliculaModel = new Pelicula($conn);
$generoModel = new Genero($conn);
$peliculaGeneroModel = new PeliculaGenero($conn);

// Crear servicio con inyección de dependencias
$peliculaService = new PeliculaService(
    $peliculaModel,
    $peliculaGeneroModel,
    $generoModel,
    $conn
);

// Crear controller
$controller = new PeliculaController($peliculaService);

// LECTURA DE DATOS DE LA PETICIÓN ///////////////////////

// Leer método HTTP utilizado
$method = $_SERVER['REQUEST_METHOD'];

// Leer JSON enviado desde el frontend
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Leer ruta enviada por .htaccess mediante: backend/index.php?route=peliculas/1


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
if ($resource !== 'peliculas') {
    return;
}

$respuesta = true; // este route de peliculas se encargará de manejar la solicitud actual

/**
 * MANEJO DE RUTAS Y RESPUESTAS
 * ===============================
 * Todas las operaciones se ejecutan dentro de
 * un bloque try/catch para manejar errores.
 */
try {

    // GET /api/peliculas - Obtener listado de películas
    if ($method === 'GET' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->index()
        ]);
        exit;
    }

    // GET /api/peliculas/completas - Obtener películas con relaciones completas (géneros, imágenes)
    if ($method === 'GET' && $param === 'completas') {
        echo json_encode([
            'success' => true,
            'data' => $controller->listarPeliculasCompletas()
        ]);
        exit;
    }

    // GET /api/peliculas/1 - Obtener película por ID
    if ($method === 'GET' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->mostrarPelicula($id)
        ]);
        exit;
    }

    // POST /api/peliculas - Crear nueva película
    if ($method === 'POST' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->guardarPelicula($body)
        ]);
        exit;
    }

    // PUT /api/peliculas/1 - Actualizar película existente
    if ($method === 'PUT' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->actualizarPelicula($id, $body)
        ]);
        exit;
    }


    // PATCH /api/peliculas/1/activar - Activar película
    if ($method === 'PATCH' && $id && $action === 'activar') {
        echo json_encode([
            'success' => true,
            'data' => $controller->activar($id)
        ]);
        exit;
    }

    // PATCH /api/peliculas/1/desactivar - Desactivar película
    if ($method === 'PATCH' && $id && $action === 'desactivar') {
        echo json_encode([
            'success' => true,
            'data' => $controller->desactivar($id)
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
