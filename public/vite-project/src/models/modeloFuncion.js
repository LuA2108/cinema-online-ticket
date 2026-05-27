import {
    obtenerFunciones,
    obtenerFuncionPorId,
    obtenerFuncionesPorEstado,
    obtenerFuncionesPorPelicula,
    obtenerEstadosFuncion,
    obtenerEstadoFuncion,
    agregarFuncion,
    actualizarFuncion,
    eliminarFuncion
} from "../api/funcionesApi.js";

class ModeloFuncion {

    constructor(
        id,
        pelicula_id,
        sala_id,
        hora,
        fecha_inicio,
        fecha_fin,
        estado_id
    ) {
        this.id = id;
        this.pelicula_id = pelicula_id;
        this.sala_id = sala_id;
        this.hora = hora;
        this.fecha_inicio = fecha_inicio;
        this.fecha_fin = fecha_fin;
        this.estado_id = estado_id;
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

    async obtenerFuncionesPorPelicula(peliculaId) {
        return await obtenerFuncionesPorPelicula(peliculaId);
    }

    async obtenerEstadosFuncion() {
        return await obtenerEstadosFuncion();
    }

    async obtenerEstadoFuncion(id) {
        return await obtenerEstadoFuncion(id);
    }

    async agregarFuncion(datos) {
        return await agregarFuncion(datos);
    }

    async actualizarFuncion(id, datos) {
        return await actualizarFuncion(id, datos);
    }

    async eliminarFuncion(id) {
        return await eliminarFuncion(id);
    }
}

export default ModeloFuncion;