import TablaView from "../componentes/tabla";

class FuncionTabla {

    constructor() {
        this.tabla = new TablaView("tablaFuncionesBody");
    }

    renderizarTablaFunciones(funciones) {

        this.tabla.limpiar();

        funciones.forEach(funcion => {

            const tr = this.tabla.crearFila([
                funcion.id,
                funcion.titulo,
                funcion.sala,
                funcion.fecha_hora
            ]);

            // ESTADO como badge simple
            const tdEstado = document.createElement("td");
            const span = document.createElement("span");

            span.textContent = funcion.estado;

            if (funcion.estado === "pendiente") {
                span.className = "badge bg-warning text-white fs-6 px-3 py-2";
            } else if (funcion.estado === "activa") {
                span.className = "badge bg-success fs-6 px-3 py-2";
            } else {
                span.className = "badge bg-secondary fs-6 px-3 py-2";
            }

            tdEstado.appendChild(span);
            this.tabla.agregarCelda(tr, tdEstado);

            // programacion_id
            this.tabla.agregarCelda(tr, this.tabla.crearCelda(funcion.programacion_id));

            this.tabla.agregarFila(tr);
        });
    }
}

export default FuncionTabla;