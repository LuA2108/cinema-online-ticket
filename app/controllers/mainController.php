<?php 

    class MainController {

        public function index() {
            $title = "Inicio - Cinema Online Ticket";

            // Capturamos la vista en un buffer
            ob_start();

            //Capturar la vista en un buffer
            require __DIR__ . '/public/index.php';
            $content = ob_get_clean();

            // Se carga el layout, que incluye los partials
            require __DIR__ . '/../views/layout/main.php';

        }

    }

?>