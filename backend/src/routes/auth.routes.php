<?php

use App\authMiddleware\AuthMiddleWare;
use App\Controllers\AuthController;
use App\Models\Usuario;
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$db = (new Database())->obtenerConexion();
$modelo = new Usuario($db);
$controlador = new AuthController($modelo);
$controlador->autenticacion($data);