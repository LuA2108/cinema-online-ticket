<?php 
    /**
     * Clase Sala
     * Gestiona metodos CRUD, excepto delete (desactiva en vez de eliminar)
     * Conexión mysqli mediante inyeccion de dependencias
     */
    class Sala {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene una lista de todas las salas
     * @return array Lista de las salas
     */
    public function listarSalas()
    {
        $sql = $this->conn->query("SELECT * FROM sala");
        return $sql->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene una lista de todas las salas activas
     * @param boolean $activa
     */
    public function listarSalaPorActivo($activa) {
        $sql = $this->conn->prepare("SELECT * FROM sala WHERE activa = ?");
        
        $activa = $activa ? 1 : 0;

        $sql->bind_param("i", $activa);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Agrega una nueva sala
     * @param mixed $numero Numero de sala
     * @param mixed $capacidad Capacidad de la sala
     */
    public function agregarSala($numero, $capacidad) {
        $sql = $this->conn->prepare("INSERT INTO sala(numero, capacidad) VALUES(?, ?)");
        $sql->bind_param("ii", $numero, $capacidad);

        return $sql->execute();
    }

    /**
     * Edita una sala existente
     * @param int $sala_id ID de la sala
     * @param int $numero Numero de sala
     * @param int $capacidad Capacidad de la sala
     * @param boolean $activa Estado de la sala
     */
    public function actualizarSala($sala_id, $numero, $capacidad, $activa) {
        $sql = $this->conn->prepare("UPDATE sala SET numero = ?, capacidad = ?, activa = ? WHERE id = ?");
        $activa = $activa ? 1 : 0;
        $sql->bind_param("iiii", $numero, $capacidad, $activa, $sala_id);
        return $sql->execute();
    }

    /**
     * Desactiva una sala existente
     * @param int $sala_id ID de la sala
     * @return bool True al ser desactivada, false al fallar
     */
    public function desactivarSala($sala_id) {
        $sql = $this->conn->prepare("UPDATE sala SET activa = FALSE WHERE id = ?");
        $sql->bind_param("i", $sala_id);
        return $sql->execute();
    }
}

?>