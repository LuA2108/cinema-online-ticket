<?php

namespace App\Service;

use App\Models\ReservaButaca;
use App\Models\Butaca;
use App\Models\Funcion;
use App\Models\Sala;

class ReservaButacaService
{
    private ReservaButaca $reservaButacaModel;
    private Butaca $butacaModel;
    private Funcion $funcionModel;
    private Sala $salaModel;

    public function __construct(ReservaButaca $reservaButacaModel, Butaca $butacaModel,Funcion $funcionModel, Sala $salaModel)
    {
        $this->reservaButacaModel = $reservaButacaModel;
        $this->butacaModel = $butacaModel;
        $this->funcionModel = $funcionModel;
        $this->salaModel = $salaModel;
    }

    /**
     * Agrega butacas a la reserva
     * @param mixed $reserva_id
     * @param mixed $funcion_id
     * @param mixed $butaca_id
     * @param mixed $precio
     * @return array
     */
    public function agregarButacaAReserva($reserva_id, $funcion_id, $butaca_id, $precio)
    {
        // 1. validar butaca
        $butaca = $this->butacaModel->obtenerPorId($butaca_id);
        if (!$butaca) {
            return ["success" => false, "error" => "La butaca no existe"];
        }

        // 2. validar función
        $funcion = $this->funcionModel->obtenerPorId($funcion_id);
        if (!$funcion) {
            return ["success" => false, "error" => "La función no existe"];
        }

        // 3. validar sala
        if ($butaca['sala_id'] != $funcion['sala_id']) {
            return ["success" => false, "error" => "La butaca no pertenece a la sala de la función"];
        }

        // 4. validar ocupación global (IMPORTANTE)
        if ($this->reservaButacaModel->estaOcupada($funcion_id, $butaca_id)) {
            return ["success" => false, "error" => "La butaca ya está reservada"];
        }

        // 5. evitar duplicado en la misma reserva (tu forma actual)
        $yaReservada = $this->reservaButacaModel->obtenerPorReserva($reserva_id);

        foreach ($yaReservada as $b) {
            if ($b['butaca_id'] == $butaca_id) {
                return ["success" => false, "error" => "Butaca ya añadida a la reserva"];
            }
        }

        // 6. insertar
        $ok = $this->reservaButacaModel->crear($butaca_id, $reserva_id, $precio);

        return $ok
            ? ["success" => true, "datos" => true]
            : ["success" => false, "error" => "Error al reservar butaca"];
    }

    /**
     * Elimina butacas en la reserva de butacas
     * @param mixed $reserva_id
     * @param mixed $funcion_id
     * @param mixed $butaca_id
     * @return array
     */
    public function eliminarButacaDeReserva($reserva_id, $funcion_id, $butaca_id)
    {
        $ok = $this->reservaButacaModel->eliminar($reserva_id, $butaca_id);

        return $ok
            ? ["success" => true, "datos" => true]
            : ["success" => false, "error" => "Error al eliminar butaca"];
    }


    /**
     * Obtiene butacas por reserva según el ID
     * @param mixed $reserva_id
     * @return array
     */
    public function obtenerButacasPorReserva($reserva_id)
    {
        $data = $this->reservaButacaModel->obtenerPorReserva($reserva_id);
        return ["success" => true, "datos" => $data, "error" => null];
    }

    /**
     * Obtiene butacas segun la función
     * @param mixed $funcion_id
     * @return array
     */
    public function obtenerButacasPorFuncion($funcion_id)
    {
        $funcion = $this->funcionModel->obtenerPorId($funcion_id);

        if (!$funcion) {
            return ["success" => false, "error" => "Función no existe"];
        }

        $data = $this->reservaButacaModel->obtenerPorFuncion($funcion_id);
        return ["success" => true, "datos" => $data, "error" => null];
    }
}