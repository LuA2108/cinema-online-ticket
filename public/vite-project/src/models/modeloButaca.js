import {obtenerButacasPorSala, obtenerMapaButacas, obtenerButacaPorId} from "../api/butacasApi.js";

class ModeloButaca {

    constructor(id, sala_id, fila, numero) {
        this.id = id;
        this.fila = fila;
        this.numero = numero;
        this.sala_id = sala_id;
    }

    /**
     * Obtener butacas de una sala
     */
    async obtenerButacasPorSala(salaId) {
        return await obtenerButacasPorSala(salaId);
    }

    /**
     * Obtener mapa de butacas por función
     */
    async obtenerMapaButacas(funcionId) {
        return await obtenerMapaButacas(funcionId);
    }

    /**
     * Obtener una butaca por ID
     */
    async obtenerButacaPorId(id) {
        return await obtenerButacaPorId(id);
    }
}

export default ModeloButaca;