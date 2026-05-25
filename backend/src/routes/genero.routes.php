<?php 
use App\Controllers\GeneroController;
use App\Service\GeneroService;
use App\Models\Genero;

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../service/GeneroService.php';
require_once __DIR__ . '/../controllers/GeneroController.php';

global $respuesta;

 // Conexión a base de datos y creación del controlador
$conn = (new Database())->obtenerConexion();

// Modelos necesarios para el controlador de géneros
$generoModel = new Genero($conn);

// Servicio de géneros
$generoService = new GeneroService($generoModel);

// Controlador de géneros
$controller = new GeneroController($generoService);

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
if ($resource !== 'generos') {
    return;
}

$respuesta = true; // este route de generos se encargará de manejar la solicitud actual

    /**
 * MANEJO DE RUTAS Y RESPUESTAS
 * ===============================
 * Todas las operaciones se ejecutan dentro de
 * un bloque try/catch para manejar errores.
 */
try {
    
    // GET /api/generos - Obtener listado de géneros
    if ($method === 'GET' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->index()
        ]);
        exit;
    }

    // GET /api/generos/completas - Obtener géneros con relaciones completas
    if ($method === 'GET' && $param === 'completas') {
        echo json_encode([
            'success' => true,
            'data' => $controller->index()
        ]);
        exit;
    }

    // GET /api/generos/1 - Obtener género por ID
    if ($method === 'GET' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->mostrarGenero($id)
        ]);
        exit;
    }

    // POST /api/generos - Crear nuevo género
    if ($method === 'POST' && !$param) {
        echo json_encode([
            'success' => true,
            'data' => $controller->crearGenero($body)
        ]);
        exit;
    }

    // PUT /api/generos/1 - Actualizar género existente
    if ($method === 'PUT' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->actualizarGenero($id, $body)
        ]);
        exit;
    }

    // DELETE /api/generos/1 - Eliminar género
    if ($method === 'DELETE' && $id) {
        echo json_encode([
            'success' => true,
            'data' => $controller->eliminarGenero($id)
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

?>