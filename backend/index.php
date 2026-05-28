<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';

// =========================================================
// HEADERS GLOBALES
// =========================================================

// Todas las respuestas serán JSON
header('Content-Type: application/json');

// Permitir acceso desde cualquier origen
header('Access-Control-Allow-Origin: *');

// Métodos HTTP permitidos
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

// Headers permitidos desde frontend
header('Access-Control-Allow-Headers: Content-Type');

/**
 * El navegador envía una petición OPTIONS
 * antes de ciertos métodos HTTP.
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener route enviada por Apache Rewrite
$route = $_GET['route'] ?? null;

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

// ==========================
// ROUTER PRINCIPAL
// ==========================
switch ($resource) {

    case 'usuarios':
        require_once __DIR__ . '/src/routes/usuarios.routes.php';
        break;

    case 'peliculas':
        require_once __DIR__ . '/src/routes/pelicula.routes.php';
        break;

    case 'generos':
        require_once __DIR__ . '/src/routes/genero.routes.php';
        break;

    case 'imagenes':
        require_once __DIR__ . '/src/routes/imagen.routes.php';
        break;

    case 'salas':
        require_once __DIR__ . '/src/routes/sala.routes.php';
        break;

    case 'butacas':
        require_once __DIR__ . '/src/routes/butaca.routes.php';
        break;

    case 'funciones':
        require_once __DIR__ . '/src/routes/funcion.routes.php';
        break;

    case 'productos':
        require_once __DIR__ . '/src/routes/producto.routes.php';
        break;

    case 'reservas':
        require_once __DIR__ . '/src/routes/reserva.routes.php';
        break;

    case 'reserva-butacas':
        require_once __DIR__ . '/src/routes/reservaButaca.routes.php';
        break;

    case 'tipoproductos':
        require_once __DIR__ . '/src/routes/tipoProducto.routes.php';
        break;

    case 'estados-reservas':
        require_once __DIR__ . '/src/routes/estadosReserva.routes.php';
        break;

    case 'login':
        require_once __DIR__ . '/src/routes/auth.routes.php';
        break;

    case 'perfil':
        require_once __DIR__ . '/src/routes/perfil.routes.php';
        break;

    case 'admin':
        require_once __DIR__ . '/src/routes/admin.routes.php';
        break;

    default:
        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "Ruta no encontrada"
        ]);
}
