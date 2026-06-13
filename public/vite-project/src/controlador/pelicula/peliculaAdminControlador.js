import ModeloPelicula from "../../models/modeloPelicula.js";
import ModeloGenero from "../../models/modeloGenero.js";
import PeliculaTabla from "../../view/pelicula/peliculaTabla.js";

class PeliculaControlador {

    constructor() {
        this.modeloPelicula = new ModeloPelicula();
        this.modeloGenero = new ModeloGenero();
        this.vistaTabla = new PeliculaTabla();

        this.modo = "crear";
    }

    init() {
        this.cargarPeliculas();
        this.registrarEventos();
        this.registrarFormulario();
    }

    // =====================
    // LISTAR
    // =====================
    async cargarPeliculas() {
        try {
            const peliculas = await this.modeloPelicula.obtenerPeliculasCompletas();
            this.vistaTabla.renderizarTablaPeliculas(peliculas);
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    // =====================
    // EVENTOS TABLA
    // =====================
    registrarEventos() {

        const tbody = document.getElementById("tablaPeliculasBody");

        tbody.addEventListener("click", (e) => {

            const btnEditar = e.target.closest(".btn-editar");
            if (btnEditar) {
                this.abrirModalEditar(btnEditar.dataset.id);
                return;
            }

            const btnEstado = e.target.closest(".btn-estado");
            console.log("Estado de boton", btnEstado)
            if (btnEstado) {

                const estado = Number(btnEstado.dataset.estado);
                const id = Number(btnEstado.dataset.id);

                if (estado == 1) {
                    this.desactivarPelicula(id);
                } else {
                    this.activarPelicula(id);
                }
                console.log("Estado de boton", estado)
                return;
            }

            const btnImagen = e.target.closest(".btn-agregar-imagen");
            if (btnImagen) {
                this.abrirGestionImagen(btnImagen);
            }
        });
    }

    // =====================
    // FORM
    // =====================
    registrarFormulario() {

        const form = document.getElementById("formPelicula");

        form.addEventListener("submit", (e) => {
            e.preventDefault();

            if (this.modo === "crear") {
                this.crearPelicula();
            } else {
                this.actualizarPelicula();
            }
        });
    }

    // =====================
    // CREAR
    // =====================
    async crearPelicula() {
        try {
            const form = document.getElementById("formPelicula");
            const formData = new FormData(form);

            const pelicula = this.mapearFormulario(formData);

            const generos = formData.getAll("generos[]");

            await this.modeloPelicula.crearPelicula({
                pelicula,
                generos
            });

            this.cerrarModal();
            await this.cargarPeliculas();

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    // =====================
    // EDITAR
    // =====================
    async abrirModalEditar(id) {
        try {
            const pelicula = await this.modeloPelicula.obtenerPeliculaPorId(id);
            console.log("PELÍCULA EDITAR:", pelicula);
            this.modo = "editar";

            document.getElementById("modalTituloPelicula").innerText = "Editar Película";
            document.getElementById("pelicula-id").value = pelicula.id;

            this.rellenarFormulario(pelicula);

            new bootstrap.Modal(document.getElementById("modalPelicula")).show();

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    async actualizarPelicula() {

        const id = document.getElementById("pelicula-id").value;
        const form = document.getElementById("formPelicula");
        const formData = new FormData(form);

        const pelicula = this.mapearFormulario(formData);
        const generos = formData.getAll("generos[]");

        const payload = {
            pelicula,
            generos
        };

        await this.modeloPelicula.actualizarPelicula(id, payload);
        this.cerrarModal();
        await this.cargarPeliculas();
    }

    // =====================
    // HELPERS
    // =====================
    mapearFormulario(formData) {
        return {
            titulo: formData.get("titulo"),
            descripcion: formData.get("descripcion"),
            director: formData.get("director"),
            anio: formData.get("anio"),
            duracion: formData.get("duracion"),
            precio: formData.get("precio"),
            disponible: formData.get("disponible") ? 1 : 0,
            destacado: formData.get("destacado") ? 1 : 0
        };
    }

    rellenarFormulario(p) {
        document.getElementById("titulo").value = p.titulo || "";
        document.getElementById("descripcion").value = p.descripcion || "";
        document.getElementById("director").value = p.director || "";
        document.getElementById("anio").value = p.anio || "";
        document.getElementById("duracion").value = p.duracion || "";
        document.getElementById("disponible").checked = p.disponible == 1;
        document.getElementById("destacado").checked = p.destacado == 1;
    }

    cerrarModal() {
        const modalEl = document.getElementById("modalPelicula");
        const modal = bootstrap.Modal.getInstance(modalEl);

        modal.hide();

        const form = document.getElementById("formPelicula");
        form.reset();

        document.getElementById("pelicula-id").value = "";
        document.getElementById("modalTituloPelicula").innerText = "Crear Película";

        this.modo = "crear";
    }

    abrirGestionImagen(btn) {
        const id = btn.dataset.peliculaId;
        const titulo = btn.dataset.peliculaTitulo;

        window.location.href =
            `/admin/imagenes/index.html?pelicula_id=${id}&pelicula_titulo=${encodeURIComponent(titulo)}&autoOpen=1`;
    }

    async activarPelicula(id) {
        try {
            await this.modeloPelicula.activarPelicula(id);
            await this.cargarPeliculas();
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    async desactivarPelicula(id) {
        try {
            await this.modeloPelicula.desactivarPelicula(id);
            await this.cargarPeliculas();
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
}

export default PeliculaControlador;