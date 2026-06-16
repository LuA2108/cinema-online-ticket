class ReservaFuncion {

    renderizarTituloPelicula(pelicula) {
        let titulo = document.querySelector(".funcion-titulo");
        titulo.replaceChildren("");
        titulo.innerText = pelicula.titulo;
    }

    renderizarPoster(url) {
        const BASE_URL = "http://localhost/cinema-online-ticket/";
        const img = document.querySelector(".funcion-poster img");
        img.replaceChildren("");
        img.src = BASE_URL + url;
    }

    renderizarSala(idSala) {
        
    }

    crearBotonHora(funcion) {
        const btn = document.createElement("button");
        btn.classList.add("hora-btn");

        btn.textContent = funcion.hora;
        btn.dataset.id = funcion.id;

        return btn;
    }

    renderizarFunciones(funciones) {

        const info = document.querySelector(".funcion-info");
        info.replaceChildren();

        const agrupadas = this.agruparPorFecha(funciones);

        for (const fecha in agrupadas) {
            let contenedorFechaHora = document.createElement("div");
            contenedorFechaHora.classList.add("tarjeta-fecha-hora");

            // etiqueta fecha
            const fechaSpan = document.createElement("span");
            fechaSpan.classList.add("funcion-fecha");
            fechaSpan.textContent = this.formatearFecha(fecha);

            // contenedor horas
            const horasDiv = document.createElement("div");
            horasDiv.classList.add("funcion-horas");

            // botones
            for (const funcion of agrupadas[fecha]) {

                const btn = this.crearBotonHora(funcion);
                horasDiv.appendChild(btn);

            }
            contenedorFechaHora.appendChild(fechaSpan);
            contenedorFechaHora.appendChild(horasDiv)
            info.appendChild(contenedorFechaHora);
        }
    }

    agruparPorFecha(funciones) {
        const agrupadas = {};

        funciones.forEach(f => {
            const fecha = f.fecha_hora.split(" ")[0];
            const hora = f.fecha_hora.split(" ")[1].slice(0, 5);

            if (!agrupadas[fecha]) agrupadas[fecha] = [];

            agrupadas[fecha].push({
                id: f.id,
                hora,
                titulo: f.titulo
            });
        });

        return agrupadas;
    }

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