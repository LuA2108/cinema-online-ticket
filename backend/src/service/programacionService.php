<?php

namespace App\Service;

use App\Models\Pelicula;
use App\Models\Programacion;
use App\Models\Funcion;
use App\Models\Sala;
use mysqli;

/**
 * Clase ProgramacionService
 * Gestiona la lógica de negocio de las programaciones.
 */
class ProgramacionService
{
    private Programacion $programacionModel;
    private Pelicula $peliculaModel;
    private Funcion $funcionModel;
    private Sala $salaModel;
    private mysqli $conn;

    /**
     * Contructor de la clase ProgramacionService
     * Se inyectan los modelos necesarios para poder realizar validaciones
     * y operaciones relacionadas entre entidades.
     * @param Programacion $programacionModel;
     * @param Pelicula $peliculaModel
     * @param Sala $salaModel
     * @param mysqli $conn
     */
    public function __construct(Programacion $programacionModel, Pelicula $peliculaModel, Funcion $funcionModel, Sala $salaModel, $conn)
    {
        $this->programacionModel = $programacionModel;
        $this->peliculaModel = $peliculaModel;
        $this->funcionModel = $funcionModel;
        $this->salaModel = $salaModel;
        $this->conn = $conn;
    }

    /**
     * Obtiene todas las programaciones existentes
     * @return array Respuesta estructurada con datos y estado de la operación
     */
    public function listarProgramaciones(): array
    {
        return ["success" => true, "datos" => $this->programacionModel->listarProgramaciones(), "error" => null];
    }

    /**
     * Obtiene los datos de una programacion según su ID
     * @param int $id 
     * @return array{datos: mixed, error: null, success: bool|array{datos: null, error: string, success: bool}}
     */
    public function obtenerProgramacion(int $id): array
    {
        if (!$this->existeProgramacion($id)) {
            return ["success" => false, "datos" => null, "error" => "La programación no existe"];
        }

        $programacion = $this->programacionModel->obtenerProgramacion($id);

        return ["success" => true, "datos" => $programacion, "error" => null];
    }

    /**
     * Crea una nueva programacion
     * Valida datos obligatorios, existencia de película y sala, además de formato de fecha y hora.
     * @param array $datos Datos de la programacion
     * @return array Resultado de la operación
     */
    public function crearProgramacion(array $datos): array
    {
        // Campos obligatorios
        $campos = ['pelicula_id', 'sala_id', 'hora', 'fecha_inicio', 'fecha_fin'];

        // Recorre cada campo
        foreach ($campos as $campo) {
            // valida que ningun datos este vacío 
            if (!isset($datos[$campo])) {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        if (!$this->existePelicula($datos['pelicula_id'])) {
            return ["success" => false, "datos" => null, "error" => "La película no existe"];
        }

        // Si no existe devueve el error, sin datos y que fallo
        if (!$this->existeSala($datos['sala_id'])) {
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

        if ($this->programacionModel->existeSolapamiento($datos['sala_id'], $datos['hora'], $datos['fecha_inicio'], $datos['fecha_fin'])) {
            return ["success" => false, "datos" => null, "error" => "Ya existe una programación con ese horario en esa sala"];
        }

        $programacion_id = $this->programacionModel->agregarProgramacion($datos['pelicula_id'], $datos['sala_id'], $datos['hora'], $datos['fecha_inicio'], $datos['fecha_fin']);

        if ($programacion_id === -1) {
            return ["success" => false, "datos" => null, "error" => "Error al crear la programacion"];
        }

        /*GENERAR FUNCIONES AUTOMÁTICAMENTE */
        $inicio = new \DateTime($datos['fecha_inicio']);
        $fin = new \DateTime($datos['fecha_fin']);
        $hora = $datos['hora'];

        while ($inicio <= $fin) {

            $fechaHora = $inicio->format('Y-m-d') . ' ' . $hora;

            $this->funcionModel->crear(
                $programacion_id,
                $fechaHora,
                1 // estado inicial: inactiva
            );

            $inicio->modify('+1 day');
        }

        return ["success" => true, "datos" => ["id" => $programacion_id], "error" => null];
    }

    /**
     * Summary of actualizarProgramacion
     * @param mixed $id
     * @param array $datos
     * @return array{datos: null, error: string, success: bool}
     */
    public function actualizarProgramacion($id, array $datos): array
    {
        if (!$this->existeProgramacion($id)) {
            return ["success" => false, "datos" => null, "error" => "La programación no existe"];
        }

        // Campos obligatorios
        $campos = ['pelicula_id', 'sala_id', 'hora', 'fecha_inicio', 'fecha_fin', 'estado'];

        // Recorre cada campo
        foreach ($campos as $campo) {
            // valida que ningun datos este vacío 
            if (!isset($datos[$campo])) {
                return ["success" => false, "datos" => null, "error" => "El campo {$campo} es obligatorio"];
            }
        }

        // Se obtiene programacion 
        $programacion = $this->programacionModel->obtenerProgramacion($id);

        // Si el estado de programacion es True no se puede actualizar, solo las inactivas
        if ((int)$programacion['estado'] !== 0) {
            return ["success" => false, "error" => "Solo se pueden editar programaciones inactivas"];
        }

        if (!$this->existePelicula($datos['pelicula_id'])) {
            return ["success" => false, "datos" => null, "error" => "La película no existe"];
        }

        // Si no existe devueve el error, sin datos y que fallo
        if (!$this->existeSala($datos['sala_id'])) {
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

        if ($this->programacionModel->existeSolapamientoEdicion($id, $datos['sala_id'], $datos['hora'], $datos['fecha_inicio'], $datos['fecha_fin'])) {
            return ["success" => false, "datos" => null, "error" => "La sala ya está ocupada en ese rango de fechas"];
        }

        try {

            $this->conn->begin_transaction();
            $ok = $this->programacionModel->actualizarProgramacion($id, $datos['pelicula_id'], $datos['sala_id'], $datos['hora'], $datos['fecha_inicio'], $datos['fecha_fin']);

            if (!$ok) {
                throw new \Exception("No se pudo actualizar la programación");
            }

            $this->funcionModel->eliminarPorProgramacion($id);

            $inicio = new \DateTime($datos['fecha_inicio']);
            $fin = new \DateTime($datos['fecha_fin']);
            $hora = $datos['hora'];

            while ($inicio <= $fin) {

                $fechaHora = $inicio->format('Y-m-d') . ' ' . $hora;
                $okFuncion = $this->funcionModel->crear($id, $fechaHora, 1);

                if (!$okFuncion) {
                    throw new \Exception("Error creando funciones");
                }

                $inicio->modify('+1 day');
            }

            $this->conn->commit();
            return ["success" => true, "datos" => true, "error" => null];
        } catch (\Throwable $e) {

            $this->conn->rollback();
            return ["success" => false, "datos" => null, "error" => $e->getMessage()];
        }
    }

    /**
     * Elimina una programacion y las funciones asociadas
     * @param int $id ID de programacion
     * @return array Resultado de la operación
     */
    public function eliminarProgramacion($id): array
    {
        // Comprueba primero si existe la programacion
        if (!$this->existeProgramacion($id)) {
            return ["success" => false, "datos" => null, "error" => "La programación no existe"];
        }

        // Obtener programacion y funciones segun la programacion
        $programacion = $this->programacionModel->obtenerProgramacion($id);

        // Comprueba el estado de la programacion, solo se permite eliminar programaciones pendientes
        if ($programacion['estado']) {
            return ["success" => false, "datos" => null, "error" => "No se puede eliminar una programación activa"];
        }

        // ELimina programacion y tambien sus funciones relacionadas por la cascada
        $resultado = $this->programacionModel->eliminarProgramacion($id);

        return $resultado ? ["success" => true, "datos" => true, "error" => null] :
            ["success" => false, "datos" => null, "error" => "Error al eliminar la programacion."];
    }

    /**
     * Obtiene una programacion por película
     * @param int $pelicula_id ID de película
     * @return array Resultado
     */
    public function obtenerProgramacionesPorPelicula($pelicula_id): array
    {
        if (!$this->existePelicula($pelicula_id)) {
            return ["success" => false, "datos" => null, "error" => "La película no existe"];
        }

        return ["success" => true, "datos" => $this->programacionModel->listarPorPelicula($pelicula_id), "error" => null];
    }


    /**
     * Cambia el estado de programación y tambien de funciones
     * @param int $programacion_id;
     * @param boolean $estado ID del estado
     * @return array Resultado
     */
    public function cambiarEstado($programacion_id, $estado): array
    {
        // Comprueba si existe programacion segun el ID
        if (!$this->existeProgramacion($programacion_id)) {
            return ["success" => false, "datos" => null, "error" => "La programación no existe"];
        }

        // Cambia el estado de programacion y luego se obtienen las funciones
        $ok = $this->programacionModel->cambiarEstado($programacion_id, $estado);
        $funciones = $this->funcionModel->obtenerPorProgramacion($programacion_id);

        // Recorre las funciones y cambia el estado de cada una de ellas
        foreach ($funciones as $funcion) {
            $this->funcionModel->cambiarEstado($funcion['id'], $estado);
        }

        if (!$ok) {
            return ["success" => false, "datos" => null, "error" => "No se ha actualizado la programacion"];
        }

        return ["success" => true, "datos" => $ok, "error" => null];
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

    /**
     * Comprueba si existe la pelicula
     * @param int $peliculaId
     * @return bool
     */
    private function existePelicula($peliculaId): bool
    {
        return (bool) $this->peliculaModel->peliculaId($peliculaId);
    }

    /**
     * Comrueba si existe la sala
     * @param int $salaId
     * @return bool
     */
    private function existeSala($salaId): bool
    {
        return (bool) $this->salaModel->listarSala($salaId);
    }

    /**
     * Comprueba si existe una programación
     * @param int $programacionId
     * @return bool
     */
    private function existeProgramacion($programacionId): bool
    {
        return (bool) $this->programacionModel->obtenerProgramacion($programacionId);
    }
}
