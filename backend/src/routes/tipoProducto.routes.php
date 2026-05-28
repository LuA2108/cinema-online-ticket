<?php

use App\Controllers\TipoProductoController;
use App\Service\TipoProductoService;
use App\Models\TipoProducto;

require_once __DIR__ . "/../../config/database.php";

require_once __DIR__ . "/../controllers/TipoProductoController.php";
require_once __DIR__ . "/../service/TipoProductoService.php";
require_once __DIR__ . "/../models/TipoProducto.php";

// VARIABLES GLOBALES DEL ROUTER CENTRAL
global $resource, $param, $action, $segments;

// CONEXIÓN E INYECCIÓN DE DEPENDENCIAS
// CONEXIÓN BD
$conn = (new Database())->obtenerConexion();

// MODELO
$tipoProductoModel = new TipoProducto($conn);

// SERVICE
$tipoProductoService = new TipoProductoService($tipoProductoModel);

// CONTROLLER
$controller = new TipoProductoController($tipoProductoService);

// DATOS DE LA PETICIÓN
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = is_numeric($param) ? (int) $param : null;

// ROUTES
try {

    // GET /tipoproductos
    if ($method === 'GET' && !$param) {
        echo json_encode($controller->index());
        exit;
    }

    // GET /tipoproductos/1
    if ($method === 'GET' && $id) {
        echo json_encode($controller->obtenerTipoProductoID($id));
        exit;
    }

    // GET /tipoproductos/buscar/ropa
    if ($method === 'GET' && $param === 'buscar' && isset($segments[2])) {
        $nombre = $segments[2];

        echo json_encode($controller->buscarPorNombreTipoProducto($nombre));
        exit;
    }

    // POST /tipoproductos
    if ($method === 'POST' && !$param) {
        echo json_encode($controller->guardarTipoProducto($body));
        exit;
    }

    // PUT /tipoproductos/1
    if ($method === 'PUT' && $id) {
        echo json_encode($controller->actualizarTipoProducto($id, $body));
        exit;
    }

    // DELETE /tipoproductos/1
    if ($method === 'DELETE' && $id) {
        echo json_encode($controller->eliminarTipoProducto($id));
        exit;
    }

    // Ruta inválida dentro del módulo
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Ruta de tipos de producto no válida"
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "datos" => null,
        "error" => $e->getMessage()
    ]);
}