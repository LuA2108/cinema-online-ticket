<?php

use App\authMiddleware\AuthMiddleWare;
require_once __DIR__ . '/../middleware/AuthMiddleWare.php';

$user = AuthMiddleWare::checkAdmin();

echo json_encode([
    "message" => "Panel admin",
    "user" => $user
]);