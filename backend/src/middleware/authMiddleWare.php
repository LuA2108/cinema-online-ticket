<?php

namespace App\authMiddleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleWare
{
    public static function verificarToken()
    {
        // Obtener y leer Authorization Header
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        // 1. comprobar que existe
        if (!$authHeader) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "error" => "Token requerido"
            ]);
            exit;
        }

        // Extraer token (quitar Bearer)
        $token = str_replace("Bearer ", "", $authHeader);

        try {
            // 2. comprobar firma + expiración
            return JWT::decode(
                $token,
                new Key("MI_CLAVE_SECRETA_LEO_DANMEI_QUIERO_UN_GATO_LLAMADO_NEFERPITOU_DE_COLOR_NEGRO", "HS256")
            );
        } catch (Exception $e) {
            http_response_code(401);

            echo json_encode([
                "error" => "Token inválido"
            ]);
            exit;
        }
    }

    public static function checkAdmin()
    {
        $user = self::verificarToken();

        if ($user->rol !== 1) {
            http_response_code(403);
            exit("Solo admin");
        }

        return $user;
    }
}
