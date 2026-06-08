import TablaView from "../componentes/tabla";
import { Boton } from "../componentes/boton";
class PeliculaTabla {

    constructor() {
        this.tabla = new TablaView("tablaPeliculasBody");
    }

    renderizarTablaPeliculas(peliculas) {

        // Limpiar tabla
        this.tabla.limpiar();

        peliculas.forEach(pelicula => {

            // COLUMNAS BASE con datos de película
            const columnas = [
                pelicula.id,
                pelicula.titulo,
                pelicula.descripcion,
                pelicula.generos || "Sin géneros",
                pelicula.director,
                pelicula.anio,
                `${pelicula.duracion} min`,
                pelicula.fecha_registro
            ];

            // Crear fila base
            const tr = this.tabla.crearFila(columnas);

            // IMAGEN POSTER //////////////////////////////

            const tdPoster = document.createElement("td");

            if (pelicula.poster) {

                const img = document.createElement("img");
                const BASE_URL = "http://localhost/cinema-online-ticket";

                img.src = pelicula.poster.startsWith("http") ? pelicula.poster : `${BASE_URL}${pelicula.poster}`;
                img.alt = pelicula.titulo;
                img.width = 70;
                img.className = "rounded shadow-sm";

                tdPoster.appendChild(img);

            } else {

                const btnAdd = document.createElement("button");
                btnAdd.className = "btn btn-sm btn-outline-primary btn-agregar-imagen";

                btnAdd.innerHTML = `<i class="fa-solid fa-image"></i> Añadir`;

                btnAdd.dataset.peliculaId = pelicula.id;
                btnAdd.dataset.peliculaTitulo = pelicula.titulo;

                tdPoster.appendChild(btnAdd);
            }

            tr.insertBefore(tdPoster, tr.children[1]);
            // DISPONIBILIDAD//////////////////////////

            const tdDisponible = document.createElement("td");
            const span = document.createElement("span");

            span.className = pelicula.disponible == 1
                ? "badge bg-success"
                : "badge bg-danger";

            span.textContent = pelicula.disponible == 1
                ? "Disponible"
                : "No disponible";

            tdDisponible.appendChild(span);
            this.tabla.agregarCelda(tr, tdDisponible);

            // BOTONES ////////////////////////

            const tdAcciones = document.createElement("td");

            const div = document.createElement("div");
            div.className = "d-flex gap-1";

            // Botón editar
            const btnEditar = Boton.crear(
                "Editar",
                "btn btn-warning btn-sm btn-editar",
                pelicula.id
            );
            // Botón desactiva/activar
            const btnEstado = document.createElement("button");
            btnEstado.className = pelicula.disponible == 1 ? "btn btn-sm btn-danger btn-estado" : "btn btn-sm btn-success btn-estado";

            btnEstado.textContent = pelicula.disponible == 1 ? "Desactivar" : "Activar";

            btnEstado.dataset.id = pelicula.id;
            btnEstado.dataset.estado = pelicula.disponible;

            div.append(btnEditar, btnEstado);
            tdAcciones.appendChild(div);
            this.tabla.agregarCelda(tr, tdAcciones);

            // AGREGAR FILA //////////
            this.tabla.agregarFila(tr);
        });
    }
}

export default PeliculaTabla;