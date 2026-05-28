<?php
namespace App\authMiddleware;

class AdminController
{
    public static function dashboard() {

        $admin = AuthMiddleware::checkAdmin();

        echo json_encode([
            "mensaje" => "Bienvenido admin",
            "admin" => $admin
        ]);
    }
}
