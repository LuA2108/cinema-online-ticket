<?php

use App\Controller\UsuarioController;
use App\Service\UsuarioService;
use App\Models\Usuario;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../service/UsuarioService.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';


global $respuesta; // Variable global para indicar si la ruta fue manejada por este módulo

// Crear conexión a base de datos
$conn = (new Database())->obtenerConexion();

// Instanciar modelo
$usuarioModel = new Usuario($conn);
// Crear servicio con inyección de dependencias
$usuarioService = new UsuarioService($usuarioModel, $conn);
// Crear controller
$controller = new UsuarioController($usuarioService);

// LECTURA DE DATOS DE LA PETICIÓN ///////////////////////
// Leer método HTTP utilizado
$method = $_SERVER['REQUEST_METHOD'];

// Leer JSON enviado desde el frontend
$body = json_decode(file_get_contents('php://input'), true) ?? [];

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
if ($resource !== 'usuarios') {
    return;
}

$respuesta = true; // Variable para indicar si este módulo manejó la ruta actual

try {

    // GET /api/usuarios - Listar todos los usuarios
    if ($method === 'GET' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->index()
        ]);
        exit;
    }

    // GET /api/usuarios/1 - Obtener usuario por ID
    if ($method === 'GET' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->mostrarUsuarioID($id)
        ]);
        exit;
    }

    // GET /api/usuarios/email - Obtener usuario por email
    if ($method === 'GET' && $param === 'email') {
        echo json_encode([
            'success' => true,
            'data' => $controller->mostrarUsuarioEmail($body['email'])
        ]);
        exit;
    }

    // POST /api/usuarios - Crear nuevo usuario
    if ($method === 'POST' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->guardar($body)
        ]);
        exit;
    }

    // PUT /api/usuarios/1 - Actualizar usuario existente
    if ($method === 'PUT' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->actualizar($id, $body)
        ]);
        exit;
    }

    // DELETE /api/usuarios/1 - Eliminar usuario existente
    if ($method === 'DELETE' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->eliminar($id)
        ]);
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
