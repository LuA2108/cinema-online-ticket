import ModeloGenero from "../../models/modeloGenero.js";
import renderizarTablaGeneros from "../../view/genero/generoTabla.js";

class GenerosControlador {

    constructor(model) {
        this.model = new ModeloGenero();

        // inicializa eventos
        this.init();
    }

    init() {
        this.cargarGeneros();

        // elementos UI
        this.btnAgregar = document.getElementById("btnAgregarGenero");
        this.formContainer = document.getElementById("formGeneroContainer");
        this.btnGuardar = document.getElementById("btnGuardarGenero");
        this.btnCancelar = document.getElementById("btnCancelarGenero");
        this.inputNombre = document.getElementById("nombreGenero");

        // abrir formulario
        this.btnAgregar.addEventListener("click", () => {
            this.formContainer.classList.remove("d-none");
        });

        // cancelar
        this.btnCancelar.addEventListener("click", () => {
            this.limpiarFormulario();
            this.formContainer.classList.add("d-none");
        });

        // guardar
        this.btnGuardar.addEventListener("click", (e) => {
            e.preventDefault();
            this.agregar();
        });

        // eventos tabla (delegación)
        const tbody = document.getElementById("tablaGenerosBody");

        tbody.addEventListener("click", (e) => {

            const btn = e.target.closest("button");

            if (!btn) return; // 🔥 clave

            const id = btn.dataset.id;

            console.log("BTN:", btn);
            console.log("DATASET:", btn.dataset);
            console.log("ID:", btn.dataset.id);

            if (!id) {
                console.error("ID no encontrado en botón");
                return;
            }

            if (btn.classList.contains("btn-editar")) {
                this.editar(id);
            }

            if (btn.classList.contains("btn-eliminar")) {
                this.eliminar(id);
            }
        });
    }

    async cargarGeneros() {
        try {
            const generos = await this.model.obtenerGeneros();

            console.log("DEBUG generos:", generos);

            if (!Array.isArray(generos)) {
                throw new Error("La API no devolvió un array");
            }

            renderizarTablaGeneros(generos);

        } catch (error) {
            console.error("Error cargando géneros:", error.message);
        }
    }

    // eliminar
    async eliminar(id) {
        try {
            await this.model.eliminarGenero(id);
            await this.cargarGeneros();
        } catch (error) {
            console.error("Error eliminando género:", error.message);
        }
    }

    // agregar género
    async agregar() {
        try {
            const nombre = this.inputNombre.value.trim();

            if (!nombre) {
                alert("El nombre del género es obligatorio");
                return;
            }

            await this.model.agregarGenero(nombre);

            this.limpiarFormulario();
            this.formContainer.classList.add("d-none");

            await this.cargarGeneros();

        } catch (error) {
            console.error("Error agregando género:", error.message);
        }
    }

    // editar (placeholder)
    async editar(id) {
        try {
            console.log("Editar género:", id);
        } catch (error) {
            console.error(error.message);
        }
    }

    limpiarFormulario() {
        this.inputNombre.value = "";
    }
}

export default GenerosControlador;