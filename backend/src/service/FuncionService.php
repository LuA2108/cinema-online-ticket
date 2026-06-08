<?php

namespace App\Service;

use App\Models\Funcion;
use App\Models\EstadoFuncion;
use App\Models\Programacion;

/**
 * Clase FuncionService
 * Esta clase se encarga de gestionar la lógica de negocio de las funciones (proyecciones de películas).
 * Actúa como intermediario entre los controladores y los modelos, aplicando validaciones,
 * reglas de negocio y devolviendo respuestas estructuradas.
 */
class FuncionService
{
    private Funcion $funcionModel;
    private EstadoFuncion $estadoFuncion;
    private Programacion $programacionModel;

    /**
     * Contructor de la clase Funcion
     * Se inyectan los modelos necesarios para poder realizar validaciones
     * y operaciones relacionadas entre entidades.
     * @param Funcion $funcionModel
     * @param EstadoFuncion $estadoFuncion
     * @param Programacion $programacionModel
     */
    public function __construct(Funcion $funcionModel, EstadoFuncion $estadoFuncion, Programacion $programacionModel)
    {
        $this->funcionModel = $funcionModel;
        $this->estadoFuncion = $estadoFuncion;
        $this->programacionModel = $programacionModel;
    }

    /**
     * Obtiene todas las funciones registradas
     * @return array Respuesta estructurada con datos y estado de la operación
     */
    public function listarFunciones(): array
    {
        return ["success" => true, "datos" => $this->funcionModel->listar(), "error" => null];
    }

    /**
     * Obtiene los datos de una función según su ID
     * @param int $id 
     * @return array{datos: mixed, error: null, success: bool|array{datos: null, error: string, success: bool}}
     */
    public function obtenerFuncionId(int $id): array
    {
        if ($id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID inválido"];
        }

        $funcion = $this->funcionModel->obtenerPorId($id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "Función no encontrada"];
        }

        return ["success" => true, "datos" => $funcion, "error" => null];
    }

    /**
     * Crea una nueva función asociada a una programación.
     * Valida programación, estado y formato de fecha_hora.
     * @param array $datos Datos de la función
     * @return array Resultado de la operación
     */
    public function crearFuncion(array $datos): array
    {
        $campos = ['programacion_id', 'fecha_hora', 'estado_id'];

        foreach ($campos as $campo) {
            if (!isset($datos[$campo])) {
                return [
                    "success" => false,
                    "datos" => null,
                    "error" => "El campo {$campo} es obligatorio"
                ];
            }
        }

        if (!$this->existeProgramacion($datos['programacion_id'])) {
            return ["success" => false, "datos" => null, "error" => "La programación no existe"];
        }

        if (!$this->existeEstado($datos['estado_id'])) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe"];
        }

        if (!$this->validarFechaHora($datos['fecha_hora'])) {
            return ["success" => false, "datos" => null, "error" => "Formato fecha_hora inválido (YYYY-MM-DD HH:MM:SS)"];
        }

        $funcionId = $this->funcionModel->crear($datos['programacion_id'], $datos['fecha_hora'], $datos['estado_id']);

        if ($funcionId < 0) return ["success" => false, "datos" => null, "error" => "Error al crear la función"];

        return ["success" => true, "datos" => ["id" => $funcionId], "error" => null];
    }

    /**
     * Actualiza una función existente
     * @param int $id ID de la función
     * @param array $datos Nuevos datos
     * @return array Resultado de la operación
     */
    public function actualizarFuncion($id, $datos): array
    {
        $campos = ['programacion_id', 'fecha_hora', 'estado_id'];

        foreach ($campos as $campo) {
            if (!isset($datos[$campo])) {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        $funcion = $this->funcionModel->obtenerPorId($id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "La función no existe"];
        }

        if (!$this->existeProgramacion($datos['programacion_id'])) {
            return ["success" => false, "datos" => null, "error" => "La programación no existe"];
        }

        if (!$this->existeEstado($datos['estado_id'])) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe"];
        }

        if (!$this->validarFechaHora($datos['fecha_hora'])) {
            return ["success" => false, "datos" => null, "error" => "Formato fecha_hora inválido"];
        }

        $ok = $this->funcionModel->actualizar($id, $datos['programacion_id'], $datos['fecha_hora'], $datos['estado_id']);

        return $ok ? ["success" => true, "datos" => true, "error" => null] :
            [
                "success" => false,
                "datos" => null,
                "error" => "Error al actualizar"
            ];
    }

    /**
     * Elimina una función
     * @param int $id ID de la función
     * @return array Resultado de la operación
     */
    public function eliminarFuncion($id): array
    {
        $funcion = $this->funcionModel->obtenerPorId($id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "No existe la función."];
        }

        $resultado = $this->funcionModel->eliminar($id);

        return $resultado ? ["success" => true, "datos" => true, "error" => null] :
            ["success" => false, "datos" => null, "error" => "Error al eliminar la función."];
    }

    /**
     * Obtiene funciones filtradas por estado
     * @param int $estado_id ID del estado
     * @return array Resultado
     */
    public function obtenerFuncionPorEstado($estado_id): array
    {
        $estado = $this->estadoFuncion->obtenerEstadoPorId($estado_id);

        if (!$estado) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe."];
        }

        return ["success" => true, "datos" => $this->funcionModel->obtenerPorEstado($estado_id), "error" => null];
    }

    /**
     * Obtiene un estado por ID
     * @param int $estadoId ID del estado
     * @return array Resultado
     */
    public function cambiarEstadoFuncion($id, $estadoId): array
    {
        if (!$this->existeEstado($estadoId)) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe"];
        }

        $funcion = $this->funcionModel->obtenerPorId($id);
        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "La función no existe"];
        }

        $estado = $this->estadoFuncion->obtenerEstadoPorId($estadoId);

        if (!$estado) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe."];
        }

        $ok = $this->funcionModel->cambiarEstado($id, $estadoId);

        return ["success" => true, "datos" => $ok, "error" => null];
    }

    /**
     * Valida formato datetime
     * YYYY-MM-DD HH:MM:SS
     */
    private function validarFechaHora(string $fechaHora): bool
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $fechaHora);

        return $dt && $dt->format('Y-m-d H:i:s') === $fechaHora;
    }

    /**
     * Comprueba si existe estado
     * @param int $estadoId
     * @return bool
     */
    private function existeEstado($estadoId): bool
    {
        return (bool)$this->estadoFuncion->obtenerEstadoPorId($estadoId);
    }

    /**
     * Comprueba si existe programacion
     * @param int $programacionId
     * @return bool
     */
    private function existeProgramacion($programacionId): bool
    {
        return (bool) $this->programacionModel->obtenerProgramacion($programacionId);
    }
}
