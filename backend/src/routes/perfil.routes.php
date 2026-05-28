<?php 

use App\authMiddleware\AuthMiddleWare;

$user = AuthMiddleWare::verificarToken();
echo json_encode([
    "message" => "perfil",
    "data" => $user
]);
?>