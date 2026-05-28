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

// VARIABLES GLOBALES DEL ROUTER CENTRAL

global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELOS
$productoModel = new Producto($conn);
$tipoProductoModel = new TipoProducto($conn);

// SERVICE
$service = new ProductoService($productoModel, $tipoProductoModel);

// CONTROLLER
$controller = new ProductoController($service);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /productos
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->listarProductos());
        exit;
    }

    // GET /productos/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerProductoPorId($id));
        exit;
    }

    // GET /productos/tipo/1
    if ($method === 'GET' && $param === 'tipo' && isset($segments[2])) {
        $tipoId = (int) $segments[2];

        echo json_encode($controller->listarProductosPorTipo($tipoId));
        exit;
    }

    // GET /productos/buscar/coca
    if ($method === 'GET' && $param === 'buscar' && isset($segments[2])) {
        $nombre = urldecode($segments[2]);

        echo json_encode($controller->obtenerProductoPorNombre($nombre));
        exit;
    }

    // POST /productos
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->agregarProducto($body));
        exit;
    }

    // PUT /productos/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarProducto($id, $body));
        exit;
    }

    // DELETE /productos/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarProducto($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Ruta de productos no válida"
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}