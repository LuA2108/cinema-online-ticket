import ModeloReserva from "../models/modelReservas.js";
import ReservaView from "../view/reservaView.js";

class ReservaControlador {

    constructor() {
        this.modelo = new ModeloReserva();
        this.vista = new ReservaView();
    }

    async cargarReservas() {

        try {

            const reservas = await this.modelo.obtenerReservas();

            this.vista.renderizarTablaReservas(reservas);

        } catch (error) {

            console.error(error);

            alert(error.message);
        }
    }
}

export default ReservaControlador;