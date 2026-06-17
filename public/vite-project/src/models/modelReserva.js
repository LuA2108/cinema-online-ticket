import { obtenerReservas, obtenerReservaPorId, obtenerReservasPorUsuario, obtenerReservasPorFuncion,
    agregarReserva, actualizarReserva, cambiarEstadoReserva, eliminarReserva
} from "../api/reservasApi.js";

class ModeloReserva {
    constructor(id, usuario_id, nombre_cliente, email_cliente, funcion_id, estado_id, fecha_reserva, total) 
    {
        this.id = id;
        this.usuario_id = usuario_id;
        this.nombre_cliente = nombre_cliente;
        this.email_cliente = email_cliente;
        this.funcion_id = funcion_id;
        this.estado_id = estado_id;
        this.fecha_reserva = fecha_reserva;
        this.total = total;
    }

    async obtenerReservas() {
        return await obtenerReservas();
    }

    async obtenerReservaPorId(id) {
        return await obtenerReservaPorId(id);
    }

    async obtenerReservasPorUsuario(usuarioId) {
        return await obtenerReservasPorUsuario(usuarioId);
    }

    async obtenerReservasPorFuncion(funcionId) {
        return await obtenerReservasPorFuncion(funcionId);
    }

    async agregarReserva(datos) {
        return await agregarReserva(datos);
    }

    async actualizarReserva(id, datos) {
        return await actualizarReserva(id, datos);
    }

    async cambiarEstadoReserva(id, estado_id) {
        return await cambiarEstadoReserva(id, estado_id);
    }

    async eliminarReserva(id) {
        return await eliminarReserva(id);
    }
}

export default ModeloReserva;