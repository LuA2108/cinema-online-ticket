import { obtenerFunciones, obtenerFuncionPorId, obtenerFuncionesPorEstado } from "../api/funcionesApi.js";

class ModeloFuncion {

    constructor(id, pelicula_titulo, sala_id, fecha_hora, estado_id, programacion_id) {
        this.id = id;
        this.pelicula_titulo = pelicula_titulo;
        this.sala_id = sala_id;
        this.fecha_hora = fecha_hora;
        this.estado_id = estado_id;
        this.programacion_id = programacion_id;
    }

    async obtenerFunciones() {
        return await obtenerFunciones();
    }

    async obtenerFuncionPorId(id) {
        return await obtenerFuncionPorId(id);
    }

    async obtenerFuncionesPorEstado(estadoId) {
        return await obtenerFuncionesPorEstado(estadoId);
    }
}

export default ModeloFuncion;