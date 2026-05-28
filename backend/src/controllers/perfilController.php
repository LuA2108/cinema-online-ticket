<?php
use App\Controller\AuthController;
use App\authMiddleware\AuthMiddleWare;

class PerfilController
{
    public function index()
    {
        $auth = new AuthMiddleWare();
        $user = $auth->verificarToken();

        echo json_encode([
            "mensaje" => "Perfil del usuario",
            "usuario" => $user
        ]);
    }
}
