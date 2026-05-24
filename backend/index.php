<?php

require_once __DIR__ . '/../vendor/autoload.php'; //Carga dependencias (Composer)
require_once __DIR__ . '/../config/database.php'; // Configura conexión a base de datos

// Todas las respuestas serán en formato JSON
header("Content-Type: application/json");

// Define headers globales (JSON + CORS)
// Permite que el frontend consuma la API desde cualquier origen
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