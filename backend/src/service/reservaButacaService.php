<?php

namespace App\Service;

use App\Models\ReservaButaca;
use App\Models\Butaca;
use App\Models\Funcion;
use App\Models\Reserva;

class ReservaButacaService
{
    private ReservaButaca $reservaButacaModel;
    private Butaca $butacaModel;
    private Funcion $funcionModel;

    private Reserva $reservaModel;

    public function __construct(ReservaButaca $reservaButacaModel, Butaca $butacaModel, Funcion $funcionModel, Reserva $reservaModel)
    {
        $this->reservaButacaModel = $reservaButacaModel;
        $this->butacaModel = $butacaModel;
        $this->funcionModel = $funcionModel;
        $this->reservaModel = $reservaModel;
    }

    /**
     * Agrega butacas a la reserva
     * @param mixed $reserva_id
     * @param mixed $butaca_id
     * @param mixed $precio
     * @return array
     */
    public function agregarButacaAReserva($reserva_id, $butaca_id, $precio)
    {
        // 1. Validar reserva
        $reserva = $this->reservaModel->obtenerReservaPorId($reserva_id);

        if (!$reserva) {
            return ["success" => false, "error" => "La reserva no existe"];
        }

        // 2. Obtener función asociada a la reserva
        $funcion = $this->funcionModel->obtenerPorId($reserva['funcion_id']);

        if (!$funcion) {
            return ["success" => false, "error" => "La función no existe"];
        }

        // 3. Validar butaca
        $butaca = $this->butacaModel->obtenerPorId($butaca_id);

        if (!$butaca) {
            return ["success" => false, "error" => "La butaca no existe"];
        }

        // 4. Validar que la butaca pertenece a la sala de la función
        if ((int)$butaca['sala_id'] !== (int)$funcion['sala_id']) {
            return ["success" => false, "error" => "La butaca no pertenece a la sala de la función"];
        }

        // 5. Verificar ocupación
        if ($this->reservaButacaModel->estaOcupada($funcion['id'], $butaca_id)) {
            return ["success" => false, "error" => "La butaca ya está reservada para esta función"];
        }

        // 6. Evitar duplicados dentro de la misma reserva
        $butacasReserva = $this->reservaButacaModel->obtenerPorReserva($reserva_id);

        foreach ($butacasReserva as $b) {
            if ((int)$b['butaca_id'] === (int)$butaca_id) {
                return ["success" => false, "error" => "La butaca ya está añadida a la reserva"];
            }
        }

        // 7. Insertar
        $ok = $this->reservaButacaModel->crear($butaca_id, $reserva_id, $precio);

        if (!$ok) {
            return ["success" => false, "error" => "Error al reservar la butaca"];
        }

        return ["success" => true, "datos" => true, "error" => null];
    }

    /**
     * Elimina butacas en la reserva de butacas
     * @param mixed $reserva_id
     * @param mixed $butaca_id
     * @return array
     */
    public function eliminarButacaDeReserva($reserva_id, $butaca_id)
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
