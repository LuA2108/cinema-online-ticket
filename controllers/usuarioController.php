<?php
//Importa modelo usuario
require_once __DIR__ . "/../models/usuario.php";

/**
 * Controlador de usuarios
 * Gestiona la logica de los usuarios, como intermediario entre el modelo y vista
 */
class UserControlador
{
    private $usuario;

    /**
     * Contructor del controlador de usuarios
     * @param mysqli $conn
     */
    public function __construct($conn)
    {
        $this->usuario = new Usuario($conn);
    }
    
    /**
     * Obtiene la lista de todos los usuarios
     * @return array Lista de usuarios
     */
    public function listarUsuarios()
    {
        return $this->usuario->obtenerUsuarios();
    }
}
