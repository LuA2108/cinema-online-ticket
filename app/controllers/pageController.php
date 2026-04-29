<?php

class pageController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    
    public function mostrarPagina($page)
    {

        $layout = "main";
        $base = __DIR__ . "/../views/public/";
        $title = "Cinema Online Ticket";
    
        // SI es admin
        if(str_starts_with($page, "admin/")) 
        {   
            // No admin
            if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
                $page = "index";    
                $base = __DIR__ . "/../views/public/";
            
            } else {
                $layout = "admin";
                $base = __DIR__ . "/../views/";
                $title = "Panel Administración";
            }
        }

        //Auth
        elseif(str_starts_with($page, "auth/")) {
            $base = __DIR__ . "/../views/";
        }

        $content = $base . $page . ".php";

        // verificar que exista
        if(!file_exists($content))
        {
            $content = __DIR__ . "/../views/404.php";
        }

        $contentView = $content;

        require __DIR__ . "/../views/layout/" .$layout. ".php";
    }
}
?>