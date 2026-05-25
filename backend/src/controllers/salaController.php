<?php 
namespace App\Controllers;
use App\Models\Sala;
use App\Service\SalaService;

class SalaController {
    private SalaService $salaService;

    /**
     * Constructor de la clase SalaService
     * @param mixed $salaService
     */
    public function __construct($salaService)
    {
        $this->salaService = $salaService;
    }

    /**
     * Controlador para listar todas las salas
     * @return array{data: array, error: null, success: bool}
     */
    public function listarSalas(){
        $respuesta = $this->salaService->listarSalas();
        return $respuesta;
    }

    /**
     * obtiene una sala por su ID
     * @param mixed $sala_id
     * @return array{datos: array, error: null, success: array{datos: null, error: string, success: bool|bool}}
     */
    public function obtenerSala($sala_id){
        $respuesta = $this->salaService->listarSala($sala_id);
        return $respuesta;
    }

    /**
     * Controlador para listar todas las salas disponibles
     * @param boolean $activa
     * @return array{data: mixed, error: null, success: bool}
     */
    public function listarActivas($activa){
        $respuesta = $this->salaService->listarActivas($activa);
        return $respuesta;
    }

    /**
     * Controlador para crear una nueva sala
     * @param int $numero
     * @param int $capacidad
     * @return array{data: mixed, error: string|null, success: mixed|array{data: null, error: string, success: bool}}
     */
    public function crearSala($numero, $capacidad){
        $respuesta = $this->salaService->crearSala($numero, $capacidad);
        return $respuesta;
    }

    /**
     * Controlador para actualizar una sala existente
     * @param int $sala_id ID de la sala a actualizar
     * @param int $numero Nuevo número de sala
     * @param int $capacidad Nueva capacidad de la sala
     * @param boolean $activa Nuevo estado de la sala
     * @return array{data: mixed, error: string|null, success: mixed|array{data: null, error: string, success: bool}}
    */       
    public function actualizarSala($sala_id, $numero, $capacidad, $activa){
        $respuesta = $this->salaService->actualizarSala($sala_id, $numero, $capacidad, $activa);
        return $respuesta;
        }

    /**
     * Controlador para desactivar una sala existente
     * @param int $id ID de la sala a desactivar
     * @return array{data: mixed, error: string|null, success: mixed|array{data: null, error: string, success: bool}}
     */
    public function desactivarSala($id){
        $respuesta = $this->salaService->desactivarSala($id);
        return $respuesta;
    }

}

?>