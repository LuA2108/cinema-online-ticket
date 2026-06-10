export default function renderMapaSala(butacas) {

    const contenedor = document.getElementById("mapaButacas");

    if (!contenedor) return;

    contenedor.innerHTML = "";

    const filas = {};

    butacas.forEach(butaca => {

        if (!filas[butaca.fila]) {
            filas[butaca.fila] = [];
        }

        filas[butaca.fila].push(butaca);
    });

    Object.keys(filas).forEach(numeroFila => {

        const filaDiv = document.createElement("div");
        filaDiv.classList.add("sala-mapa__fila");

        const label = document.createElement("div");
        label.classList.add("sala-mapa__label");
        label.textContent = `F${numeroFila}`;

        filaDiv.appendChild(label);

        filas[numeroFila].forEach(butaca => {

            const asiento = document.createElement("div");

            asiento.classList.add("sala-mapa__butaca");
            asiento.textContent = butaca.numero;

            filaDiv.appendChild(asiento);
        });

        contenedor.appendChild(filaDiv);
    });
}