// views/components/BotonView.js
export class Boton {

    static crear(texto, clases, id) {

        const btn = document.createElement("button");

        btn.textContent = texto;
        btn.className = clases;
        btn.dataset.id = id;

        return btn;
    }
}