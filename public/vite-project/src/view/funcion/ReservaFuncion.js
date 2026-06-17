class ReservaFuncion {

    // Muestra el título de la película en la interfaz
    renderizarTituloPelicula(pelicula) {
        let titulo = document.querySelector(".funcion-titulo");
        titulo.replaceChildren("");
        titulo.innerText = pelicula.titulo;
    }

    // Renderiza el póster de la película
    renderizarPoster(url) {
        const BASE_URL = "http://localhost/cinema-online-ticket/";
        const img = document.querySelector(".funcion-poster img");
        img.replaceChildren("");
        img.src = BASE_URL + url;
    }

    renderizarSala(idSala) {
        // (pendiente de implementación)
    }

    // Crea un botón para una función (hora + sala)
    crearBotonHora(funcion) {
        const btn = document.createElement("button");
        btn.classList.add("hora-btn");

        btn.dataset.id = funcion.id;

        // Contenido del botón con hora y sala
        btn.innerHTML = `
            <span class="hora">${funcion.hora}</span>
            <span class="sala">Sala ${funcion.sala}</span>
        `;

        return btn;
    }

    // Renderiza las funciones agrupadas por fecha
    renderizarFunciones(funciones) {

        const info = document.querySelector(".funcion-info");
        info.replaceChildren();

        const agrupadas = this.agruparPorFecha(funciones);

        for (const fecha in agrupadas) {
            let contenedorFechaHora = document.createElement("div");
            contenedorFechaHora.classList.add("tarjeta-fecha-hora");

            // Fecha formateada
            const fechaSpan = document.createElement("span");
            fechaSpan.classList.add("funcion-fecha");
            fechaSpan.textContent = this.formatearFecha(fecha);

            // Contenedor de horas
            const horasDiv = document.createElement("div");
            horasDiv.classList.add("funcion-horas");

            // Crear botones por función
            for (const funcion of agrupadas[fecha]) {
                const btn = this.crearBotonHora(funcion);
                horasDiv.appendChild(btn);
            }

            contenedorFechaHora.appendChild(fechaSpan);
            contenedorFechaHora.appendChild(horasDiv);
            info.appendChild(contenedorFechaHora);
        }
    }

    // Agrupa funciones por fecha
    agruparPorFecha(funciones) {
        const agrupadas = {};

        funciones.forEach(f => {
            const fecha = f.fecha_hora.split(" ")[0];
            const hora = f.fecha_hora.split(" ")[1].slice(0, 5);

            if (!agrupadas[fecha]) agrupadas[fecha] = [];

            agrupadas[fecha].push({
                id: f.id,
                hora,
                sala: f.sala, // ahora se incluye la sala
                titulo: f.titulo
            });
        });

        return agrupadas;
    }

    // Formatea la fecha a formato legible en español
    formatearFecha(fechaStr) {
        const fecha = new Date(fechaStr);

        return fecha.toLocaleDateString("es-ES", {
            weekday: "long",
            day: "2-digit",
            month: "long"
        });
    }
}

export default ReservaFuncion;