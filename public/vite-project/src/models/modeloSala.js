import {
    obtenerSalas,
    obtenerSalaPorId,
    obtenerSalasPorEstado,
    agregarSala,
    cambiarEstadoSala
} from "../api/salaApi.js";

class ModeloSala {

    constructor(id, filas, butacas_por_fila, activa) {
        this.id = id;
        this.filas = filas;
        this.butacas_por_fila = butacas_por_fila;
        this.activa = activa;
    }

    async obtenerSalas() {
        return await obtenerSalas();
    }

    async obtenerSalaPorId(id) {
        return await obtenerSalaPorId(id);
    }

    async obtenerSalasPorEstado(estado = true) {
        return await obtenerSalasPorEstado(estado);
    }

    async agregarSala(datos) {
        return await agregarSala(datos);
    }

    async cambiarEstadoSala(id, estado) {
        return await cambiarEstadoSala(id, estado);
    }

}

export default ModeloSala;