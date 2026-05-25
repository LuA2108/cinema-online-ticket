<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';

// =========================================================
// HEADERS GLOBALES
// =========================================================



// Todas las respuestas serán JSON
header('Content-Type: application/json');

// Permitir acceso desde cualquier origen
header('Access-Control-Allow-Origin: *');

// Métodos HTTP permitidos
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

// Headers permitidos desde frontend
header('Access-Control-Allow-Headers: Content-Type');

/**
 * El navegador envía una petición OPTIONS
 * antes de ciertos métodos HTTP.
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 
$respuesta = false;

// ==========================
// CARGA DE MÓDULOS (ROUTERS)
// ==========================
require_once __DIR__ . '/src/routes/pelicula.routes.php';
require_once __DIR__ . '/src/routes/genero.routes.php';

// Aquí más rutas para otros módulos (...)


// SI NINGÚN MÓDULO RESPONDIÓ
if (!$respuesta) {
    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'Ruta no encontrada'
    ]);
}
?>