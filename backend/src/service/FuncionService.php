<?php

namespace App\Service;

use App\Models\Funcion;
use App\Models\EstadoFuncion;
use App\Models\Pelicula;
use App\Models\Sala;

/**
 * Clase FuncionService
 * Esta clase se encarga de gestionar la lógica de negocio de las funciones (proyecciones de películas).
 * Actúa como intermediario entre los controladores y los modelos, aplicando validaciones,
 * reglas de negocio y devolviendo respuestas estructuradas.
 */
class FuncionService
{
    private Funcion $funcionModel;
    private Pelicula $peliculaModel;
    private EstadoFuncion $estadoFuncion;
    private Sala $salaModel;

    /**
     * Contructor de la clase Funcion
     * Se inyectan los modelos necesarios para poder realizar validaciones
     * y operaciones relacionadas entre entidades.
     * @param Funcion $funcionModel
     * @param Pelicula $peliculaModel
     * @param EstadoFuncion $estadoFuncion
     * @param Sala $salaModel
     */
    public function __construct(Funcion $funcionModel, Pelicula $peliculaModel, EstadoFuncion $estadoFuncion, Sala $salaModel)
    {
        $this->funcionModel = $funcionModel;
        $this->peliculaModel = $peliculaModel;
        $this->estadoFuncion = $estadoFuncion;
        $this->salaModel = $salaModel;
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
        $funcion = $this->funcionModel->obtenerPorId($id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "Función no encontrada"];
        }

        return ["success" => true, "datos" => $funcion, "error" => null];
    }

    /**
     * Crea una nueva función (proyección de película)
     * Valida datos obligatorios, existencia de película y sala, además de formato de fecha y hora.
     * @param array $datos Datos de la función
     * @return array Resultado de la operación
     */
    public function crearFuncion(array $datos): array
    {
        // Campos obligatorios
        $campos = ['pelicula_id', 'sala_id', 'hora', 'fecha_inicio', 'fecha_fin', 'estado_id'];

        // Recorre cada campo
        foreach ($campos as $campo) {
            // valida que ningun datos este vacío 
            if (!isset($datos[$campo])) {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        // Se busca una pelicula existente
        $pelicula = $this->peliculaModel->peliculaId($datos['pelicula_id']);

        // Si no existe devueve el error, sin datos y que fallo
        if (!$pelicula) {
            return ["success" => false, "datos" => null, "error" => "La película no existe"];
        }

        // Busca la sala según el ID
        $sala = $this->salaModel->listarSala($datos['sala_id']);

        // Si no existe devueve el error, sin datos y que fallo
        if (!$sala) {
            return ["success" => false, "datos" => null, "error" => "La sala no existe"];
        }

        // VALIDAR FORMATO: HORA -> HH:MM:SS - FORMATO FECHA (inicio y fin) -> YYYY-MM-DD 
        // Comprobar hora
        if (!$this->validarHora($datos['hora'])) {
            return ["success" => false, "datos" => null, "error" => "Formato de hora inválido. Usar HH:MM:SS"];
        }

        // Comprobar fecha inicio y fecha fin
        if (!$this->validarFecha($datos['fecha_inicio']) || !$this->validarFecha($datos['fecha_fin'])) {
            return ["success" => false, "datos" => null, "error" => "Fecha inválida (YYYY-MM-DD)"];
        }

        // Comprobar que las fechas sean coherentes
        if (!$this->validarRangoFechas($datos['fecha_inicio'], $datos['fecha_fin'])) {
            return ["success" => false, "datos" => null, "error" => "La fecha_fin no puede ser menor que fecha_inicio"];
        }

        $funcion_id = $this->funcionModel->crear($datos['pelicula_id'], $datos['sala_id'], $datos['hora'], $datos['fecha_inicio'], $datos['fecha_fin'], $datos['estado_id']);

        if ($funcion_id === -1) {
            return ["success" => false, "datos" => null, "error" => "Error al crear la función"];
        }

        return ["success" => true, "datos" => ["id" => $funcion_id], "error" => null];
    }

    /**
     * Actualiza una función existente
     * @param int $id ID de la función
     * @param array $datos Nuevos datos
     * @return array Resultado de la operación
     */
    public function actualizarFuncion($id, $datos): array
    {
        $funcion = $this->funcionModel->obtenerPorId($id);

        if (!$funcion) {
            return ["success" => false, "datos" => null, "error" => "La Función no existe"];
        }

        $respuesta = $this->funcionModel->actualizar($id, $datos['pelicula_id'], $datos['sala_id'], $datos['hora'], $datos['fecha_inicio'], $datos['fecha_fin'], $datos['estado_id']);

        return $respuesta ? ["success" => true, "datos" => true, "error" => null] :
            ["success" => false, "datos" => null, "error" => "Error al actualizar"];
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
     * Obtiene funciones por película
     * @param int $pelicula_id ID de película
     * @return array Resultado
     */
    public function obtenerFuncionesPorPelicula($pelicula_id): array
    {
        $pelicula = $this->peliculaModel->peliculaId($pelicula_id);

        if (!$pelicula) {
            return ["success" => false, "datos" => null, "error" => "No existe la película"];
        }

        return ["success" => true, "datos" => $this->funcionModel->obtenerPorPelicula($pelicula_id), "error" => null];
    }

    /**
     * Obtiene todos los estados
     * @return array Lista de estados
     */
    public function obtenerEstados(): array
    {
        return ["success" => true, "datos" => $this->estadoFuncion->obtenerEstados(), "error" => null];
    }

    /**
     * Obtiene un estado por ID
     * @param int $estadoId ID del estado
     * @return array Resultado
     */
    public function obtenerEstado($estadoId): array
    {
        $estado = $this->estadoFuncion->obtenerEstadoPorId($estadoId);

        if (!$estado) {
            return ["success" => false, "datos" => null, "error" => "El estado no existe."];
        }

        return ["success" => true, "datos" => $estado, "error" => null];
    }

    /**
     * Valida una fecha con formato YYYY-MM-DD
     * @param string $fecha
     * @return bool
     */
    private function validarFecha(string $fecha): bool
    {
        $fechaFormato = \DateTime::createFromFormat('Y-m-d', $fecha);
        return $fechaFormato && $fechaFormato->format('Y-m-d') === $fecha;
    }

    /**
     * Valida una hora con formato HH:MM:SS
     * @param string $hora
     * @return bool
     */
    private function validarHora(string $hora): bool
    {
        $horaFormato = \DateTime::createFromFormat('H:i:s', $hora);

        return $horaFormato && $horaFormato->format('H:i:s') === $hora;
    }

    /**
     * Valida que una fecha fin no sea menor a la fecha inicio
     * @param string $fechaInicio Formato YYYY-MM-DD
     * @param string $fechaFin Formato YYYY-MM-DD
     * @return bool 
     */
    private function validarRangoFechas(string $fechaInicio, string $fechaFin): bool
    {
        $inicio = \DateTime::createFromFormat('Y-m-d', $fechaInicio);
        $fin = \DateTime::createFromFormat('Y-m-d', $fechaFin);
        return $fin >= $inicio;
    }
}
