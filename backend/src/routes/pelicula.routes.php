<?php

use App\Controller\PeliculaController;
use App\Service\PeliculaService;
use App\Models\Genero;
use App\Models\PeliculaGenero;
use App\Models\Pelicula;

require_once __DIR__ . '/../config/Database.php';

/**
 * Conexión a base de datos y creación del controlador
 */
$conn = (new Database())->obtenerConexion();

// Modelos necesarios para el controlador de películas
$peliculaModel = new Pelicula($conn);
$generoModel = new Genero($conn);
$peliculaGeneroModel = new PeliculaGenero($conn);

// Servicio de películas
$peliculaService = new PeliculaService($peliculaModel, $generoModel, $peliculaGeneroModel, $conn);

// Controlador de películas
$controller = new PeliculaController($peliculaService);

/**
 * Se obtiene la URI y el método HTTP de la petición
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// GET /api/peliculas - Listar todas las películas
if ($uri === '/api/peliculas' && $method === 'GET') {
    echo json_encode($controller->index());
    exit;
}

/* GET /api/peliculas/{id} - Obtiene una pelicula */
if (preg_match('#^/api/peliculas/(\d+)$#', $uri, $m) && $method === 'GET') {
    echo json_encode($controller->mostrarPelicula($m[1]));
    exit;
}

// POST /api/peliculas - Crear una nueva película
if ($uri === '/api/peliculas' && $method === 'POST') {
    echo json_encode($controller->guardarPelicula());
    exit;
}

// PUT /api/peliculas - Actualizar una película existente
if ($uri === '/api/peliculas' && $method === 'PUT') {
    echo json_encode($controller->actualizarPelicula());
    exit;
}

// POST /api/peliculas/{id}/activar - Activar una película
if (preg_match('#^/api/peliculas/(\d+)/activar$#', $uri, $m)) {
    echo json_encode($controller->activar($m[1]));
    exit;
}

// POST /api/peliculas/{id}/desactivar - Desactivar una película
if (preg_match('#^/api/peliculas/(\d+)/desactivar$#', $uri, $m)) {
    echo json_encode($controller->desactivar($m[1]));
    exit;
}
