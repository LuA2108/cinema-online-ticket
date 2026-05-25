<?php

use App\Controllers\FuncionController;
use App\Service\FuncionService;
use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use App\Models\EstadoFuncion;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../controllers/funcionController.php";
require_once __DIR__ . "/../service/funcionService.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/funcion.php";
require_once __DIR__ . "/../models/sala.php";
require_once __DIR__ . "/../models/estadoFuncion.php";

global $respuesta;

// Crear conexión a base de datos
$conn = (new Database())->obtenerConexion();

// Instanciar modelos
$funcion = new Funcion($conn);
$pelicula = new Pelicula($conn);
$sala = new Sala($conn);
$estadoFuncion = new EstadoFuncion($conn);

// Instanciar servicio con inyección de dependencias
$funcionService = new FuncionService($funcion, $pelicula, $estadoFuncion, $sala);

// Crear controller con inyección de dependencias
$controller = new FuncionController($funcionService);

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
if ($resource !== 'funciones') {
    return;
}

$respuesta = true; // Este módulo manejará la solicitud actual

try {

    // GET /api/funciones
    // Obtener listado de funciones
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->listarFunciones());
        exit;
    }

    // GET /api/funciones/1
    // Obtener función por ID
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerFuncionId($id));
        exit;
    }

    // GET /api/funciones/estado/1
    // Obtener funciones por estado
    if ($method === 'GET' && $param === 'estado' && isset($segments[2])) {
        $estado_id = (int)$segments[2];
        echo json_encode($controller->obtenerFuncionesPorEstado($estado_id));
        exit;
    }

    // GET /api/funciones/pelicula/1
    // Obtener funciones por película
    if ($method === 'GET' && $param === 'pelicula' && isset($segments[2])) {
        $pelicula_id = (int)$segments[2];
        echo json_encode($controller->obtenerFuncionesPorPelicula($pelicula_id));
        exit;
    }

    // GET /api/funciones/estados
    // Obtener todos los estados
    if ($method === 'GET' && $param === 'estados') {
        echo json_encode($controller->obtenerEstados());
        exit;
    }

    // GET /api/funciones/estado/1
    // Obtener un estado específico
    if ($method === 'GET' && $param === 'estado-id' && isset($segments[2])) {
        $estado_id = (int)$segments[2];
        echo json_encode($controller->obtenerEstado($estado_id));
        exit;
    }

    // POST /api/funciones
    // Crear nueva función
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->crearFuncion($body));
        exit;
    }

    // PUT /api/funciones/1
    // Actualizar función
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarFuncion($id, $body));
        exit;
    }

    // DELETE /api/funciones/1
    // Eliminar función
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarFuncion($id));
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
