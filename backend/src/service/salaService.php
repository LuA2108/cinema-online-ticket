<?php

namespace App\Service;

use App\Models\Sala;

/**
 * Clase servicio de Sala
 * Interactúa con el modelo de Sala para realizar operaciones CRUD, excepto delete (desactiva en vez de eliminar)
 * Realiza validaciones y formatea las respuestas para el controlador
 * Utiliza inyección de dependencias para el modelo de Sala 
 */
class SalaService
{
    private Sala $salaModel;

    /**
     * Constructor de la clase SalaService
     * @param mixed $salaModel
     */
    public function __construct($salaModel)
    {
        $this->salaModel = $salaModel;
    }

    /**
     * Listar todas las salas 
     * @return array{data: array, error: null, success: bool}
     */
    public function listarSalas()
    {
        return ["success" => true, "datos" => $this->salaModel->listarSalas(), "error" => null];
    }

    /**
     * Listar una sala por su ID
     * @param mixed $sala_id
     * @return array{datos: array, error: null, success: bool|array{datos: null, error: string, success: bool}}
     */
    public function listarSala($sala_id)
    {
        if ($sala_id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID inválido"];
        }

        return ["success" => true, "datos" => $this->salaModel->listarSala($sala_id), "error" => null];
    }

    /**
     * Listar todas las salas disponibles
     * @param boolean $activa
     * @return array{data: mixed, error: null, success: bool}
     */
    public function listarActivas($activa)
    {
        return ["success" => true, "datos" => $this->salaModel->listarSalaPorActivo($activa),"error" => null];
    }

    /**
     * Crea una nueva sala con el número y capacidad proporcionados
     * @param int $numero
     * @param int $capacidad
     * @return array{data: mixed, error: string|null, success: mixed|array{data: null, error: string, success: bool}}
     */
    public function crearSala($numero, $capacidad)
    {
        if (empty($numero) || empty($capacidad)) {
            return ["success" => false, "datos" => null, "error" => "Número y capacidad son requeridos"];
        }

        $resultado = $this->salaModel->agregarSala($numero, $capacidad);

        if ($resultado > 0) {
            return [
                "success" => true,
                "data" => $resultado,
                "error" => null
            ];
        } else {
            return [
                "success" => false,
                "data" => null,
                "error" => "No se pudo crear la sala"
            ];
        }
    }

    /**
     * Actualiza los datos de una sala existente
     * @param int $id
     * @param int $numero
     * @param int $capacidad
     * @param boolean $activa
     * @return array{data: mixed, error: string|null, success: mixed|array{data: null, error: string, success: bool}}
     */
    public function actualizarSala($id, $numero, $capacidad, $activa)
    {
        if ($id <= 0) {
            return [
                "success" => false,
                "data" => null,
                "error" => "ID inválido"
            ];
        }

        $ok = $this->salaModel->actualizarSala($id, $numero, $capacidad, $activa);

        return [
            "success" => $ok,
            "data" => $ok,
            "error" => $ok ? null : "No se pudo actualizar la sala"
        ];
    }

    /**
     * Desactiva una sala existente
     * @param int $id ID de la sala a desactivar
     * @return array{data: mixed, error: string|null, success: mixed|array{data: null, error: string, success: bool}}
     */
    public function desactivarSala($id)
    {
        if ($id <= 0) {
            return [
                "success" => false,
                "data" => null,
                "error" => "ID inválido"
            ];
        }

        $ok = $this->salaModel->desactivarSala($id);

        return [
            "success" => $ok,
            "data" => $ok,
            "error" => $ok ? null : "No se pudo desactivar la sala"
        ];
    }
}