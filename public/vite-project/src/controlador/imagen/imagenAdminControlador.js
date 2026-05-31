import ModeloImagen from "../../models/modeloImagen.js";
import ModeloPelicula from "../../models/modeloPelicula.js";
import ImagenTabla from "../../view/imagen/imagenTabla.js";

class ImagenAdminControlador {

    constructor() {
        this.modelo = new ModeloImagen();
        this.modeloPelicula = new ModeloPelicula();
        this.vista = new ImagenTabla();
    }

    async init() {

        await this.cargarImagenes();
        this.inicializarModal();
        this.inicializarPreviewImagen();
        const modal = document.getElementById("modalImagen");

        // Al abrir formulario emergente
        modal.addEventListener("show.bs.modal", () => {

            const peliculaId = document.getElementById("pelicula_id").value;
            if (!peliculaId) {
                this.prepararModoSelector();
            }
        });

        // Al cerrar el formulario emergente
        modal.addEventListener("hidden.bs.modal", () => {
            this.limpiarModal();
        });

        // Obtener formulario para guardar imagen
        const form = document.getElementById("formImagen");

        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                await this.agregarImagen();
            });
        }

        const tbody = document.getElementById("tablaImagenesBody");

        if (tbody) {
            tbody.addEventListener("click", async (e) => {
                const boton = e.target.closest(".btn-eliminar");
                if (!boton) return;
                await this.eliminarImagen(boton.dataset.id);
            });
        }

    }

    async agregarImagen() {

        try {

            const form = document.getElementById("formImagen");
            const formData = new FormData(form);
            let peliculaId = document.getElementById("pelicula_id").value;

            if (!peliculaId) {
                peliculaId = document.getElementById("pelicula_id_select").value;
            }

            formData.set(
                "pelicula_id",
                peliculaId
            );

            await this.modelo.agregarImagen(
                formData
            );

            alert("Imagen agregada correctamente");
            await this.cargarImagenes();

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    async eliminarImagen(id) {

        try {

            if (!confirm("¿Eliminar imagen?")) {
                return;
            }

            await this.modelo.eliminarImagen(id);
            await this.cargarImagenes();

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    async obtenerImagenPorId(id) {
        try {
            return await this.modelo.obtenerImagenPorId(id);
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    async obtenerImagenPorPelicula(idPelicula) {
        try {
            return await this.modelo.obtenerImagenPorPelicula(idPelicula);
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    inicializarModal() {

        const params = new URLSearchParams(window.location.search);

        const peliculaId = params.get("pelicula_id");
        const peliculaTitulo = params.get("pelicula_titulo");
        const autoOpen = params.get("autoOpen");

        if (peliculaId && autoOpen === "1") {

            this.abrirModalDesdePelicula(
                peliculaId,
                peliculaTitulo
            );

            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );
        }
    }

    abrirModalDesdePelicula(peliculaId, peliculaTitulo) {

        document.getElementById("pelicula_id").value = peliculaId;
        document.getElementById("textoModoImagen").textContent = "Agregar imagen a:";
        document.getElementById("tituloPeliculaModal").textContent = peliculaTitulo || "";
        document.getElementById("grupoSelectPelicula").classList.add("d-none");
        const modal = new bootstrap.Modal(document.getElementById("modalImagen"));

        modal.show();
    }

    async prepararModoSelector() {

        document.getElementById("pelicula_id").value = "";
        document.getElementById("textoModoImagen").textContent = "Selecciona película:";
        document.getElementById("tituloPeliculaModal").textContent = "";
        document.getElementById("grupoSelectPelicula").classList.remove("d-none");
        await this.cargarSelectPeliculas();
    }

    async cargarSelectPeliculas() {

        const select = document.getElementById("pelicula_id_select");
        const peliculas = await this.cargarPeliculasSinImagen();
        select.innerHTML = '<option value="">Seleccione una película</option>';

        peliculas.forEach(pelicula => {

            const option = document.createElement("option");
            option.value = pelicula.id;
            option.textContent = pelicula.titulo;
            select.appendChild(option);
        });
    }

    inicializarPreviewImagen() {

        const input = document.querySelector('input[name="imagen"]');

        if (!input) return;
        input.addEventListener("change", this.mostrarPreviewImagen.bind(this));
    }

    mostrarPreviewImagen(event) {

        const file = event.target.files[0];
        const icon = document.getElementById("uploadIcon");
        const text = document.getElementById("uploadText");
        const preview = document.getElementById("previewImagen");

        if (!file) {

            icon.classList.remove("d-none");
            text.classList.remove("d-none");
            preview.classList.add("d-none");
            preview.src = "";
            return;
        }

        const reader = new FileReader();

        reader.onload = e => {

            preview.src = e.target.result;
            preview.classList.remove("d-none");
            icon.classList.add("d-none");
            text.classList.add("d-none");
        };

        reader.readAsDataURL(file);
    }

    async cargarImagenes() {
        try {

            const imagenes = await this.modelo.obtenerImagenes();
            const peliculas = await this.modeloPelicula.obtenerPeliculasCompletas();

            // Array para los datos de las imágenes
            const imagenesCompletas = [];

            for (const img of imagenes) {
                let titulo = "Sin película";

                for (const pelicula of peliculas) {
                    if (pelicula.id == img.pelicula_id) {
                        titulo = pelicula.titulo;
                        break;
                    }
                }

                imagenesCompletas.push({
                    id: img.id,
                    titulo_pelicula: titulo,
                    tipo: img.tipo,
                    url: img.url
                });
            }

            this.vista.renderizarTablaImagenes(imagenesCompletas);

        } catch (error) {

            console.error(error);
            alert(error.message);
        }
    }

    async cargarPeliculasSinImagen() {

        const peliculas = await this.modeloPelicula.obtenerPeliculasCompletas();
        const imagenes = await this.modelo.obtenerImagenes();

        return peliculas.filter(pelicula => {
            return !imagenes.find(img => Number(img.pelicula_id) === Number(pelicula.id));
        });
    }

    limpiarModal() {

        document.getElementById("pelicula_id").value = "";
        document.getElementById("tituloPeliculaModal").textContent = "";
        document
            .getElementById("grupoSelectPelicula")
            .classList.remove("d-none");
    }
}

export default ImagenAdminControlador;