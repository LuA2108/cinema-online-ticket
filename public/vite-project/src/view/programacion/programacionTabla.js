import Tabla from "../componentes/tabla";
import { Boton } from "../componentes/boton";

class ProgramacionTabla {
    constructor() {
        this.tabla = new Tabla("tablaProgramacionesBody");
    }

    renderizarTablaProgramacion(programaciones) {
        this.tabla.limpiar();

        const hoy = new Date();

        programaciones.forEach(p => {

            const fechaFin = new Date(p.fecha_fin);

            const vencida = fechaFin < hoy;
            const activa = Number(p.estado) === 1;

            // =========================
            // COLUMNAS BASE
            // =========================
            const columnas = [
                p.id,
                p.titulo,
                p.sala_id,
                p.hora,
                p.fecha_inicio,
                p.fecha_fin,
                p.precio + " $",
            ];

            const tr = this.tabla.crearFila(columnas);

            // =========================
            // ESTADO
            // =========================
            const tdEstado = document.createElement("td");
            const span = document.createElement("span");

            if (vencida) {
                span.className = "badge bg-danger px-3 py-2";
                span.textContent = "Finalizada";
            }
            else if (activa) {
                span.className = "badge bg-success px-3 py-2";
                span.textContent = "Activa";
            }
            else {
                span.className = "badge bg-warning px-3 py-2";
                span.textContent = "Borrador";
            }

            tdEstado.appendChild(span);
            this.tabla.agregarCelda(tr, tdEstado);

            // =========================
            // ACCIONES
            // =========================
            const tdAcciones = document.createElement("td");
            const div = document.createElement("div");
            div.className = "d-flex gap-1";

            // SOLO BORRADOR puede editar
            if (!vencida && Number(p.estado) === 0) {

                const btnEditar = Boton.crear(
                    "Editar",
                    "btn btn-warning btn-sm btn-editar",
                    p.id
                );

                const btnEstado = document.createElement("button");
                btnEstado.className = "btn btn-success btn-sm btn-estado";
                btnEstado.textContent = "Activar";
                btnEstado.dataset.id = p.id;
                btnEstado.dataset.estado = p.estado;

                const btnEliminar = Boton.crear(
                    "Eliminar",
                    "btn btn-danger btn-sm btn-eliminar",
                    p.id
                );

                div.append(btnEditar, btnEstado, btnEliminar);
            }

            // BLOQUEADAS (activa o finalizada)
            if (activa || vencida) {

                const badge = document.createElement("span");
                badge.className = "badge bg-secondary px-3 py-2";
                badge.textContent = "Bloqueada";

                div.appendChild(badge);
            }

            tdAcciones.appendChild(div);
            this.tabla.agregarCelda(tr, tdAcciones);

            this.tabla.agregarFila(tr);
        });
    }
}

export default ProgramacionTabla;