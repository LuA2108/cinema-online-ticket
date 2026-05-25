<?php

use App\Controllers\TipoProductoController;
use App\Service\TipoProductoService;
use App\Models\TipoProducto;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../controllers/TipoProductoController.php";
require_once __DIR__ . "/../service/TipoProductoService.php";
require_once __DIR__ . "/../models/TipoProducto.php";

global $respuesta;

// Crear conexión a base de datos
$conn = (new Database())->obtenerConexion();

// Instanciar modelo
$tipoProductoModel = new TipoProducto($conn);

// Instanciar service con inyección de dependencias
$tipoProductoService = new TipoProductoService($tipoProductoModel);

// Instanciar controller
$controller = new TipoProductoController($tipoProductoService);


// LECTURA DE DATOS DE LA PETICIÓN ///////////////////////

// Método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Body JSON
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Ruta desde rewrite
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

// Convertir ruta en segmentos
$segments = $route === '' ? [] : explode('/', $route);

// Recurso principal
$resource = $segments[0] ?? null;

// Parámetro (ID o acción)
$param = $segments[1] ?? null;

// ID numérico si aplica
$id = is_numeric($param) ? (int) $param : null;

/* SI NO ES ESTE MÓDULO, SALIR */
if ($resource !== 'tipoproductos') {
    return;
}

$respuesta = true;

try {

    /**
     * GET /api/tipoproductos
     * Listar todos los tipos de producto
     */
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    /**
     * GET /api/tipoproductos/1
     * Obtener tipo de producto por ID
     */
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerTipoProductoID($id));
        exit;
    }

    /**
     * GET /api/tipoproductos/buscar/ropa
     * Buscar por nombre
     */
    if ($method === 'GET' && $param === 'buscar' && isset($segments[2])) {
        $nombre = $segments[2];
        echo json_encode($controller->buscarPorNombreTipoProducto($nombre));
        exit;
    }

    /**
     * POST /api/tipoproductos
     * Crear tipo de producto
     */
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->guardarTipoProducto($body));
        exit;
    }

    /**
     * PUT /api/tipoproductos/1
     * Actualizar tipo de producto
     */
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarTipoProducto($id, $body));
        exit;
    }

    /**
     * DELETE /api/tipoproductos/1
     * Eliminar tipo de producto
     */
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarTipoProducto($id));
        exit;
    }
} catch (Exception $e) {

    // Manejo global de errores
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}
