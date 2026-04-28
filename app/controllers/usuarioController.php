<?php 
    //Importa modelo usuario
    require_once __DIR__ . "/../models/usuario.php";
    
    /**
     * Controlador de usuarios
     * Gestiona la logica de los usuarios, como intermediario entre el modelo y vista
     */
    class UserControlador {

    private $usuario;

    /**
     * Constructor del controlador de usuarios
     * @param $usuario Objeto del modelo usuario 
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
    
    /**
     * Obtiene la lista de todos los usuarios
     * @return array Lista de usuarios
     */
    public function listarUsuarios() {
        return $this->usuario->obtenerUsuarios();
    }

    public function login($email, $contrasena) {
        $usuario = $this->usuario->buscarPorEmail($email);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            session_start();
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['email'] = $usuario['email'];
            echo "Correcto";
            return true;
        } else {
            echo "Contraseña incorrecta.";
        }

        return false;
    }

    }
?>