<?php

namespace App\Controllers;

use App\Models\Usuario;
use Firebase\JWT\JWT;

/**
 * Clase que gestiona la autenticacion de usuarios
 */
class AuthController
{
    private Usuario $usuarioModel;

    public function __construct(Usuario $usuarioModel) {
        $this->usuarioModel = $usuarioModel;
    }

    /**
     * 
     * @param mixed $datos
     * @return void
     */
    public function autenticacion($datos)
    {
        $usuario = $this->usuarioModel->buscarPorEmail($datos["email"]);

        if (!$usuario || !password_verify($datos["contrasena"], $usuario["contrasena"])) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales incorrectas"]);
            return;
        }

        $payload = [
            "id" => $usuario["id"],
            "nombre" => $usuario["nombre"] ? $usuario["nombre"] : "-",
            "email" => $usuario["email"],
            "rol" => $usuario["rol_id"],
            "exp" => time() + 3600
        ];

        $jwt = JWT::encode(
            $payload,
            "MI_CLAVE_SECRETA_LEO_DANMEI_QUIERO_UN_GATO_LLAMADO_NEFERPITOU_DE_COLOR_NEGRO",
            "HS256"
        );

        echo json_encode([
            "token" => $jwt
        ]);
    }
}
