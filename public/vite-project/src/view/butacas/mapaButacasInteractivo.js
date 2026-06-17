class MapaButacasInteractivo {

    renderizarMapa(butacas, ocupadas) {

        const mapa = document.querySelector(".mapa-sala");

        if (!mapa) {
            console.error("No existe .mapa-sala en el DOM");
            return;
        }

        mapa.replaceChildren();

        const filas = this.agruparPorFila(butacas);

        for (const fila in filas) {

            const filaDiv = document.createElement("div");
            filaDiv.classList.add("fila");

            filas[fila].forEach(butaca => {
                const btn = document.createElement("button");

                btn.classList.add("butaca");
                btn.dataset.id = butaca.id;
                btn.dataset.fila = butaca.fila;
                btn.dataset.numero = butaca.numero;
                btn.textContent = butaca.numero;

                if (ocupadas.includes(Number(butaca.id))) {
                    btn.classList.add("ocupada");
                    btn.disabled = true;
                } else {
                    btn.classList.add("libre");
                }

                filaDiv.appendChild(btn);
            });

            mapa.appendChild(filaDiv);
        }
    }

    renderResumen(seleccionadas, total) {
        const ul = document.querySelector(".resumen-lista");
        ul.innerHTML = "";

        const vacio = document.querySelector(".resumen-vacio");

        if (seleccionadas.length === 0) {
            vacio.style.display = "block";
        } else {
            vacio.style.display = "none";
        }

        seleccionadas.forEach(b => {
            const li = document.createElement("li");
            li.textContent = `Fila ${b.fila} - Butaca ${b.numero}`;
            ul.appendChild(li);
        });

        document.querySelector(".total span").textContent = total + "€";
    }

    agruparPorFila(butacas) {
        const filas = {};

        butacas.forEach(b => {
            if (!filas[b.fila]) {
                filas[b.fila] = [];
            }

            filas[b.fila].push(b);
        });

        return filas;
    }

}

export default MapaButacasInteractivo;