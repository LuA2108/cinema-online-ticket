<?php
/**
 * Clase ROl
 * Gestiona metodos CRUD: consultas, crear, actualizar y desactivar
 * Conexión mysqli mediante inyeccion de dependencia
 */
class Rol
{
    private $conn;

    /**
     * Constructor
     * @param mysqli $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function listarRoles()
    {
        $sql = $this->conn->query("SELECT * FROM rol");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene un rol segun el ID
     * @param int $rol_id ID rol
     * @return array|null 
     */
    public function obtenerRol($rol_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM rol WHERE id = ?");
        $sql->bind_param("i", $rol_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    /**
     * Crea un nuevo rol
     * @param string $tipo Tipo de rol
     * @param string $descripcion Descripcion del rol
     * @return bool
     */
    public function agregarRol($tipo, $descripcion)
    {
        $sql = $this->conn->prepare("INSERT INTO rol(tipo, descripcion) VALUES(?, ?)");
        $sql->bind_param("ss", $tipo, $descripcion);
        return $sql->execute();
    }

    /**
     * Actualiza los datos de un rol
     * @param int $rol_id ID del rol
     * @param string $tipo Tipo de rol
     * @param string $descripcion Descripción del rol
     * @param boolean $activo Estado del rol
     * @return bool
     */
    public function actualizarRol($rol_id, $tipo, $descripcion, $activo)
    {
        $sql = $this->conn->prepare("UPDATE rol SET tipo = ?, descripcion = ?, activo = ? WHERE id = ?");
        $activo = (int)$activo;
        $sql->bind_param("ssii", $tipo, $descripcion, $activo, $rol_id);
        return $sql->execute();
    }

    /**
     * Desactiva un rol
     * @param int $rol_id ID del rol
     * @return bool
     */
    public function desactivarRol($rol_id)
    {
        $sql = $this->conn->prepare("UPDATE rol SET activo = FALSE WHERE id = ?");
        $sql->bind_param("i", $rol_id);
        
        return $sql->execute();
    }
}
