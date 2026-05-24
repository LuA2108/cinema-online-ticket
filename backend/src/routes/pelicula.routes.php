<?php

use App\Controller\PeliculaController;

/**
 * Conexión a base de datos y creación del controlador
 */
$conn = (new Database())->obtenerConexion();
$controller = new PeliculaController($conn);

/**
 * Se obtiene la URI y el método HTTP de la petición
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

/* GET todas */
if ($uri === '/api/peliculas' && $method === 'GET') {
    echo json_encode($controller->index());
    exit;
}

/* GET por ID */
if (preg_match('#^/api/peliculas/(\d+)$#', $uri, $m) && $method === 'GET') {
    echo json_encode($controller->mostrarPelicula($m[1]));
    exit;
}

/* POST crear */
if ($uri === '/api/peliculas' && $method === 'POST') {
    echo json_encode($controller->guardarPelicula());
    exit;
}

/* PUT actualizar */
if ($uri === '/api/peliculas' && $method === 'PUT') {
    echo json_encode($controller->actualizarPelicula());
    exit;
}

/* activar */
if (preg_match('#^/api/peliculas/(\d+)/activar$#', $uri, $m)) {
    echo json_encode($controller->activar($m[1]));
    exit;
}

/* desactivar */
if (preg_match('#^/api/peliculas/(\d+)/desactivar$#', $uri, $m)) {
    echo json_encode($controller->desactivar($m[1]));
    exit;
}
