<?php

namespace App\Service;

use App\Models\EstadoReserva;

/**
 * Service de EstadoReserva
 * Maneja lógica mínima de catálogo
 */
class EstadoReservaService
{
    private EstadoReserva $model;

    public function __construct(EstadoReserva $model)
    {
        $this->model = $model;
    }

    /**
     * Listar estados
     * @return array
     */
    public function listar(): array
    {
        return ["success" => true, "datos" => $this->model->obtenerEstados(), "error" => null];
    }

    /**
     * Obtener estado por ID
     * @param int $id Id de estado de reseva
     * @return array
     */
    public function obtenerPorId(int $id): array
    {
        if ($id <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID inválido"];
        }

        $estado = $this->model->obtenerEstado($id);

        if (!$estado) {
            return ["success" => false, "datos" => null, "error" => "Estado no encontrado"];
        }

        return ["success" => true, "datos" => $estado, "error" => null];
    }
}
