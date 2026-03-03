<?php

class MainController
{
    public function index()
    {
        $title = "Inicio - Cinema Online Ticket";

        // Captura la vista en un buffer
        $viewPath = __DIR__ . '/../views/public/index.php';
        if (!file_exists($viewPath)) die("Vista no encontrada: $viewPath");

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Carga el layout
        $layoutPath = __DIR__ . '/../views/layout/main.php';
        if (!file_exists($layoutPath)) die("Layout no encontrado: $layoutPath");
        require $layoutPath;
    }
}
