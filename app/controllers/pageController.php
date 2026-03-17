<?php

class pageController
{
    public function mostrarPaginas($page)
    {
        if(str_starts_with($page, "admin/")) 
        {
            $title = "Panel administracion - Cinema Online Ticket";
            $layout =  __DIR__ . "/../views/layout/admin.php";
            $view =__DIR__ . "/../views/admin".$page."/index.php";

            if(!file_exists($view)) {
                $view = __DIR__ . "/../views/".$page.".php";
            }
        }
        else    
        {
            $title = "Inicio - Cinema Online Ticket";
            $layout = __DIR__ . "/../views/layout/main.php";
            $view = __DIR__ . "/../views/public/".$page.".php";
        }

        // verificar que exista
        if(!file_exists($view))
        {
            if(str_starts_with($page,"admin/")) 
            {
                $view = __DIR__ . "/../views/admin/index.php";
            }
            else 
            {
                $view = __DIR__ . "/../views/public/index.php";
            }
        }

        // pasar la vista al layout
        $content = $view;

        require $layout;
    }
}
