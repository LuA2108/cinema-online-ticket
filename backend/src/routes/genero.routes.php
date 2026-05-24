<?php 
use App\Controller\GeneroController;
use App\Service\GeneroService;
use App\Models\Genero;

require_once __DIR__ . '/../config/Database.php';


/**
 * Conexión a base de datos y creación del controlador
 */
$conn = (new Database())->obtenerConexion();

// Modelos necesarios para el controlador de géneros
$generoModel = new Genero($conn);

// Servicio de géneros
$generoService = new GeneroService($generoModel);

// Controlador de géneros
$controller = new GeneroController($generoService);

/**
 * Se obtiene la URI y el método HTTP de la petición
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

/* GET todas */
if ($uri === '/api/generos' && $method === 'GET') {
    echo json_encode($controller->index());
    exit;
}

/* GET por ID */
if (preg_match('#^/api/generos/(\d+)$#', $uri, $m) && $method === 'GET') {
    echo json_encode($controller->mostrarGenero($m[1]));
    exit;
}

/* POST crear */
if ($uri === '/api/generos' && $method === 'POST') {
    echo json_encode($controller->crearGenero($_POST['nombre'] ?? null));
    exit;
}

/* PUT actualizar */
if (preg_match('#^/api/generos/(\d+)$#', $uri, $m) && $method === 'PUT') {
    // Para PUT, se asume que los datos vienen en formato JSON
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($controller->actualizarGenero($m[1], $data['nombre'] ?? null));
    exit;
}

/* DELETE eliminar */
if (preg_match('#^/api/generos/(\d+)$#', $uri, $m) && $method === 'DELETE') {
    echo json_encode($controller->eliminarGenero($m[1]));
    exit;
}

?>