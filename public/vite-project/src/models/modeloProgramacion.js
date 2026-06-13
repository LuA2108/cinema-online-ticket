import {
        obtenerProgramaciones, obtenerProgramacionPorId,
        obtenerProgramacionesPorPelicula, crearProgramacion,
        actualizarProgramacion, cambiarEstadoProgramacion,
        eliminarProgramacion
} from "../api/programacionApi";

class ModeloProgramacion {

        constructor(id, pelicula_id, sala_id, hora, fecha_inicio, fecha_fin, precio, estado) {
                this.id = id;
                this.pelicula_id = pelicula_id;
                this.sala_id = sala_id;
                this.hora = hora;
                this.fecha_inicio = fecha_inicio;
                this.fecha_fin = fecha_fin;
                this.precio = precio;
                this.estado = estado;
        }

        async obtenerProgramaciones() {
                return await obtenerProgramaciones();
        }

        async obtenerProgramacion(id) {
                return await obtenerProgramacionPorId(id);
        }

        async obtenerProgramacionesPorPelicula(pelicula_id) {
                return await obtenerProgramacionesPorPelicula(pelicula_id);
        }

        async crearProgramacion(datos) {
                return await crearProgramacion(datos);
        }

        async actualizarProgramacion(programacion_id, datos) {
                return await actualizarProgramacion(programacion_id, datos);
        }

        async cambiarEstadoProgramacion(programacion_id, estado) {
                return await cambiarEstadoProgramacion(programacion_id, estado);
        }

        async eliminarProgramacion(programacion_id) {
                return await eliminarProgramacion(programacion_id);
        }

        esVigente(fechaFin) {
                const hoy = new Date();
                const fin = new Date(fechaFin);
                return fin >= hoy;
        }
}

export default ModeloProgramacion;