<?php 
use App\Controller\PerfilController;
require_once __DIR__ . '/../controllers/PerfilController.php';

$controller = new PerfilController();
$controller->index();
?>