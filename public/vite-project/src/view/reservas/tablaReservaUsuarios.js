import Tabla from "../componentes/tabla";

class TablaReservaUsuarios {

    constructor() {
        this.tabla = new Tabla("tablaReservas");
    }

    renderizarTabla(reservas) {
        this.tabla.limpiar();

        if (!reservas || reservas.length === 0) {
            const fila = this.tabla.crearFila(["No tienes reservas", "", "", "", ""]);
            this.tabla.agregarFila(fila);
            return;
        }

        reservas.forEach(reserva => {
            const id = reserva.id;
            const pelicula = reserva.funcion?.titulo ?? "Sin datos";
            const sala = reserva.funcion?.sala ? `Sala ${reserva.sala.id}`  : "-";

            const fechaHora = reserva.funcion?.fecha_hora
                ? new Date(reserva.funcion.fecha_hora).toLocaleString("es-ES", {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit"
                })
                : "-";

            const total = `${Number(reserva.total || 0).toFixed(2)} €`;
            const fila = this.tabla.crearFila([id,pelicula,sala,fechaHora,total]);
            this.tabla.agregarFila(fila);
        });
    }

}

export default TablaReservaUsuarios;