<?php

use App\Controllers\ProductoController;
use App\Service\ProductoService;
use App\Models\Producto;
use App\Models\TipoProducto;

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/producto.php";
require_once __DIR__ . "/../models/tipoProducto.php";
require_once __DIR__ . "/../service/productoService.php";
require_once __DIR__ . "/../controllers/productoController.php";

global $respuesta;

// Conexión a base de datos
$conn = (new Database())->obtenerConexion();

// Instanciar modelos
$productoModel = new Producto($conn);
$tipoProductoModel = new TipoProducto($conn);

// Instanciar service
$service = new ProductoService($productoModel, $tipoProductoModel);

// Instanciar controller
$controller = new ProductoController($service);

// Leer método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Leer body JSON
$body = json_decode(file_get_contents("php://input"), true) ?? [];

// Obtener route
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

// Convertir route en segmentos
// Ejemplo:
// productos/tipo/1
$segments = $route === '' ? []: explode('/', $route);

// Recurso principal 
$resource = $segments[0] ?? null;

// Parámetro principal
$param = $segments[1] ?? null;

// Acción adicional
$action = $segments[2] ?? null;

// Convertir parámetro a ID
$id = is_numeric($param) ? (int)$param : null;

// Verificar módulo
if ($resource !== 'productos') {
    return;
}

// Marcar respuesta
$respuesta = true;

try {

    // GET /productos
    // Obtener todos los productos
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->listarProductos());
        exit;
    }

    // GET /productos/1
    // Obtener producto por ID
    if ($method === 'GET' && $id) 
    {
        echo json_encode($controller->obtenerProductoPorId($id));
        exit;
    }

    // GET /productos/tipo/1
    // Obtener productos por tipo
    if ($method === 'GET' && $param === 'tipo' && isset($segments[2])) 
    {
        $tipoId = (int)$segments[2];
        echo json_encode($controller->listarProductosPorTipo($tipoId));
        exit;
    }

    // GET /productos/buscar/coca
    // Buscar productos por nombre
    if ($method === 'GET' && $param === 'buscar' && isset($segments[2])) {

        $nombre = urldecode($segments[2]);
        echo json_encode($controller->obtenerProductoPorNombre($nombre));
        exit;
    }

    // POST /productos
    // Crear producto
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->agregarProducto($body));
        exit;
    }

    // PUT /productos/1
    // Actualizar producto
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarProducto($id, $body));
        exit;
    }

    // DELETE /productos/1
    // Eliminar producto
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarProducto($id));
        exit;
    }

} catch (Exception $e) {

    /**
     * Error interno del servidor
     */
    http_response_code(500);
    echo json_encode(["success" => false, "datos" => null, "error" => $e->getMessage()]);
}
