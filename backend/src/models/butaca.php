<?php

namespace App\Models;

/**
 * Clase Butaca
 * Lee datos de butaca
 * Usa una conexion mysqli mediante inyección de dependencias
 */
class Butaca
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene todas las butacas de una sala específica
     * @param int $sala_id
     * @return array Array de butacas o error
     */
    public function obtenerPorSala($sala_id)
    {
        $sql = $this->conn->prepare(
            "SELECT * FROM butaca WHERE sala_id = ?"
        );

        $sql->bind_param("i", $sala_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los datos de una Butaca segun su ID
     * @param int $butaca_id
     * @return array|null Array asociativo con los datos de la butaca o null
     */
    public function obtenerPorId($butaca_id)
    {
        $sql = $this->conn->prepare("SELECT * FROM butaca WHERE id = ?");
        $sql->bind_param("i", $butaca_id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Agrega una butaca a una sala específica con número y fila
     * @param int $sala_id
     * @param int $numero
     * @param int $fila
     * @return int ID de la butaca insertada o -1 en caso de error
     */
    public function insertarButaca($sala_id, $numero, $fila)
    {
        $sql = $this->conn->prepare("INSERT INTO butaca (sala_id, numero, fila) VALUES (?, ?, ?)");

        $sql->bind_param("iii", $sala_id, $numero, $fila);
        $resultado = $sql->execute();

        if ($resultado) {
            return $this->conn->insert_id;
        }
        return -1;
    }

    public function eliminarPorSala($sala_id)
    {
        $sql = $this->conn->prepare("DELETE FROM butaca WHERE sala_id = ?");
        $sql->bind_param("i", $sala_id);
        return $sql->execute();
    }
}
