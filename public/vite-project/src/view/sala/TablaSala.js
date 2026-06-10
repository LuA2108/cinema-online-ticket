import TablaView from "../componentes/tabla";
import { Boton } from "../componentes/boton";

class SalaTabla {

    constructor() {
        this.tabla = new TablaView("tablaSalasBody");
    }

    renderizarTablaSalas(salas) {

        this.tabla.limpiar();

        salas.forEach(sala => {

            // COLUMNAS BASE
            const columnas = [
                sala.id,
                sala.filas,
                sala.butacas_por_fila
            ];

            // Crear fila
            const tr = this.tabla.crearFila(columnas);

            // ESTADO
            const tdEstado = document.createElement("td");
            const span = document.createElement("span");

            const activa = Number(sala.activa);

            span.className = activa === 1
                ? "badge bg-success fs-6 px-3 py-2"
                : "badge bg-danger fs-6 px-3 py-2";

            span.textContent = activa === 1 ? "Activa" : "Inactiva";

            tdEstado.appendChild(span);

            this.tabla.agregarCelda(tr, tdEstado);

            // ACCIONES
            const tdAcciones = document.createElement("td");

            const div = document.createElement("div");
            div.className = "d-flex gap-1";

            const btnEstado = document.createElement("button");

            // Ver butacas
            const btnButacas = Boton.crear(
                "Butacas",
                "btn btn-primary btn-sm btn-butacas",
                sala.id
            );

            btnEstado.className = activa === 1
                ? "btn btn-danger btn-sm btn-estado"
                : "btn btn-success btn-sm btn-estado";

            btnEstado.textContent = activa === 1 ? "Desactivar" : "Activar";

            btnEstado.dataset.id = sala.id;
            btnEstado.dataset.estado = activa;

            div.append(btnButacas, btnEstado);

            tdAcciones.appendChild(div);

            this.tabla.agregarCelda(tr, tdAcciones);

            // AGREGAR FILA
            this.tabla.agregarFila(tr);
        });
    }
}

export default SalaTabla;