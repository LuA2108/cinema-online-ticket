<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

header("Content-Type: application/json");

// CORS (importante para frontend)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// conexión BD
$conn = (new Database())->obtenerConexion();

// ==========================
// MÓDULOS (ROUTES)
// ==========================
require_once __DIR__ . '/../src/routes/pelicula.routes.php';
// Aquí más rutas para otros módulos (géneros, salas, ...)

?>