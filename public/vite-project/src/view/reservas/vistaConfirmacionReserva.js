class vistaConfirmacionReserva {

    // Muestra el usuario logueado en la interfaz
    renderizarUsuario(usuario) {
        const contenedor = document.getElementById("usuario-logueado");
        if (!contenedor) return;

        contenedor.innerHTML = "";

        // Si no hay usuario, se oculta el bloque
        if (!usuario) {
            contenedor.style.display = "none";
            return;
        }

        contenedor.style.display = "block";
        contenedor.innerHTML = `👤 Conectado como: <b>${usuario.nombre}</b>`;
    }

    // Renderiza el resumen completo de la reserva
    renderizarResumen(reserva) {
        if (!reserva) return;

        this.renderizarPelicula(reserva.pelicula);
        this.renderizarSala(reserva.funcion);
        this.renderizarFechaHora(reserva.funcion);
        this.renderizarButacas(reserva.butacas);
        this.renderizarTotal(reserva.butacas);
    }

    // Título de la película
    renderizarPelicula(pelicula) {
        document.getElementById("res-pelicula").textContent = pelicula?.titulo || "-";
    }

    // Sala de la función
    renderizarSala(funcion) {
        document.getElementById("res-sala").textContent =
            `Sala ${funcion?.sala_id || "-"}`;
    }

    // Fecha y hora formateada de la función
    renderizarFechaHora(funcion) {
        if (!funcion?.fecha_hora) return;

        const fecha = new Date(funcion.fecha_hora);

        const formateada = fecha.toLocaleString("es-ES", {
            weekday: "long",
            day: "2-digit",
            month: "long",
            hour: "2-digit",
            minute: "2-digit"
        });

        document.getElementById("res-fecha").textContent = formateada;
    }

    // Lista de butacas seleccionadas
    renderizarButacas(butacas) {
        const ul = document.getElementById("res-butacas");
        ul.innerHTML = "";

        if (!butacas || butacas.length === 0) {
            ul.innerHTML = `<li>No hay butacas seleccionadas</li>`;
            return;
        }

        butacas.forEach(b => {
            const li = document.createElement("li");
            li.textContent = `Fila ${b.fila} - Butaca ${b.numero}`;
            ul.appendChild(li);
        });
    }

    // Cálculo del total de la compra
    renderizarTotal(butacas) {
        const total = (butacas || []).reduce(
            (acumulador, b) => acumulador + Number(b.precio),
            0
        );

        document.getElementById("res-total").textContent = `${total}€`;
    }

    // Obtiene datos del formulario de confirmación
    obtenerFormulario() {
        return {
            nombre: document.getElementById("nombre").value,
            email: document.getElementById("email").value
        };
    }

    // Evento para confirmar reserva
    bindConfirmar(callback) {
        document
            .querySelector(".btn-confirmar-reserva")
            .addEventListener("click", callback);
    }

    // Rellena el formulario con datos del usuario logueado
    rellenarFormulario(usuario) {
        if (!usuario) return;

        const nombreInput = document.getElementById("nombre");
        const emailInput = document.getElementById("email");

        if (nombreInput) {
            nombreInput.value = usuario.nombre || "";
            nombreInput.readOnly = true;
        }

        if (emailInput) {
            emailInput.value = usuario.email || "";
            emailInput.readOnly = true;
        }
    }
}

export default vistaConfirmacionReserva;