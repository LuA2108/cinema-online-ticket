export default class TablaView {

    constructor(tbodyId) {
        console.log("TablaView recibió:", tbodyId);
        this.tbody = document.getElementById(tbodyId);
        if (!this.tbody) {
            throw new Error(`No existe el elemento tbody con id: ${tbodyId}`);
        }
    }

    limpiar() {
        this.tbody.textContent = "";
    }

    crearCelda(valor) {
        const td = document.createElement("td");
        td.textContent = valor;
        return td;
    }

    crearFila(columnas) {

        const tr = document.createElement("tr");

        columnas.forEach(col => {
            tr.appendChild(this.crearCelda(col));
        });

        return tr;
    }

    agregarCelda(tr, celda) {
        tr.appendChild(celda);
    }

    agregarFila(tr) {
        this.tbody.appendChild(tr);
    }
}