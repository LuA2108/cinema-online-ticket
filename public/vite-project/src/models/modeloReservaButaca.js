import { agregarButacaAReserva, eliminarButacaDeReserva, obtenerButacasPorReserva, obtenerButacasPorFuncion } from "../api/reservaButacas";

class ModeloReservaButaca {
    
    async agregarButaca(datos) {
        return await agregarButacaAReserva(datos);
    }

    async eliminarButaca(datos) {
        return await eliminarButacaDeReserva(datos);
    }

    async obtenerPorReserva(reservaId) {
        return await obtenerButacasPorReserva(reservaId);
    }

    async obtenerPorFuncion(funcionId) {
        return await obtenerButacasPorFuncion(funcionId);
    }
}

export default ModeloReservaButaca;