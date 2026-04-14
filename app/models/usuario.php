<?php
require_once 'config/database.php';

/**
 * Clase que gestiona a los usuarios:
 * crear, editar, eliminar y consultar datos
 * Utilizanado una conexion a la BD para ejecutar las consultas sql
 * @author lucero Anay Cahuana
 */
class Usuario
{   
    private $conn;
    private $id;
    private $id_rol;
    private $nombre;
    private $email;
    private $contrasena;
    private $ciudad;
    private $provincia;
    private $fechaRegistro;


    /**
     * Constructor de la clase
     * Recibe una conexión a la BD mediante inyección de dependencias
     * @param mysqli $conn Conexión a la BD
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Obtiene todos de usuarios de la BD
     * @return array Lista de usuarios
     */
    public function obtenerUsuarios() {
        $sql = "SELECT * FROM usuarios";
        $resultado = $this->conn->query($sql);
        
        $usuarios = [];
        while($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }
    
    // Getters y setters de las propiedades de la clase
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getIdRol()
    {
        return $this->id_rol;
    }

    public function setIdRol($id_rol)
    {
        $this->id_rol = $id_rol;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }

    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
        return $this;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
        return $this;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }

    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
        return $this;
    }

    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;
        return $this;
    }
}
