class ComprobanteReserva {

    renderizar(reserva) {
        this.renderCliente(reserva);
        this.renderPelicula(reserva);
        this.renderFuncion(reserva);
        this.renderButacas(reserva.butacas);
        this.renderTotal(reserva.butacas);
    }

    renderCliente(reserva) {
        document.getElementById("res-nombre").textContent = reserva.cliente?.nombre || "No especificado";

        document.getElementById("res-email").textContent = reserva.cliente?.email || "-";
    }

    renderPelicula(reserva) {
        document.getElementById("res-pelicula").textContent = reserva.pelicula?.titulo || "-";
    }

    renderFuncion(reserva) {
        const funcion = reserva.funcion || {};

        document.getElementById("res-sala").textContent = funcion.sala_id ? `Sala ${funcion.sala_id}` : "-";
        document.getElementById("res-fecha").textContent = funcion.fecha_hora ? new Date(funcion.fecha_hora).toLocaleDateString("es-ES") : "-";
        document.getElementById("res-horario").textContent = funcion.fecha_hora ? new Date(funcion.fecha_hora)
            .toLocaleTimeString("es-ES", {
                hour: "2-digit",
                minute: "2-digit"
            }) : "-";
    }

    renderButacas(butacas = []) {
        const ul = document.getElementById("res-butacas");
        ul.innerHTML = "";

        if (!butacas.length) {
            ul.innerHTML = "<li>No hay butacas</li>";
            return;
        }

        butacas.forEach(b => {
            const li = document.createElement("li");
            li.textContent = `Fila ${b.fila} - Butaca ${b.numero}`;
            ul.appendChild(li);
        });
    }

    renderTotal(butacas = []) {
        const total = butacas.reduce((acumulador, b) => acumulador + Number(b.precio || 0), 0);
        document.getElementById("res-total").textContent = total;
    }

    bindDescargarPDF(llamada) {
        document.getElementById("descargar-pdf")?.addEventListener("click", llamada);
    }
}


export default ComprobanteReserva;