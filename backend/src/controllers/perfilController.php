<?php
namespace App\Controller; 
use App\Controller\AuthController;
use App\authMiddleware\AuthMiddleWare;

require_once __DIR__ . "/../middleware/authMiddleWare.php";

class PerfilController {
    public function index() {
        $auth = new AuthMiddleWare();
        $user = $auth->verificarToken();

        echo json_encode([
            "success" => true,
            "usuario" => $user
        ]);
        exit;
    }
}