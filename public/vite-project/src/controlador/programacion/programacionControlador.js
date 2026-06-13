import ModeloProgramacion from "../../models/modeloProgramacion";
import ModeloPelicula from "../../models/modeloPelicula";
import ModeloSala from "../../models/modeloSala";
import ProgramacionTabla from "../../view/programacion/programacionTabla";

class ProgramaControlador {

    constructor() {
        this.tabla = new ProgramacionTabla();
        this.modelo = new ModeloProgramacion();
        this.modeloPelicula = new ModeloPelicula();
        this.modeloSala = new ModeloSala();
    }

    init() {
        this.mostrarProgramaciones();
        this.eventoCrear();
        this.eventoEditar();
        this.guardarFormulario();
        this.cerrarModal();
        this.eventoEliminar();
        this.eventoCambiarEstado();
    }

    async mostrarProgramaciones() {
        try {
            const programaciones = await this.modelo.obtenerProgramaciones();
            const peliculas = await this.modeloPelicula.obtenerPeliculas();

            programaciones.forEach(p => {
                const pelicula = peliculas.find(
                    pel => pel.id === p.pelicula_id
                );
                p.titulo = pelicula ? pelicula.titulo : "Desconocida";
            });

            this.tabla.renderizarTablaProgramacion(programaciones);
        } catch (error) {
            console.error(error);
        }
    }

    guardarFormulario() {

        const formulario = document.getElementById("formProgramacion");

        formulario.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = this.obtenerDatosFormulario();

            const id = document.getElementById("programacion-id").value;

            if (id) {
                await this.modelo.actualizarProgramacion(id, datos);

            } else {
                await this.modelo.crearProgramacion(datos);
            }

            await this.mostrarProgramaciones();

            bootstrap.Modal.getInstance(
                document.getElementById("modalProgramacion")
            )?.hide();

            this.limpiarFormulario();
        });
    }

    eventoCrear() {

        document.addEventListener("click", async (e) => {
            const btn = e.target.closest("#btnAbrirProgramacion");
            if (!btn) return;
            await this.abrirModalCrear();
        });
    }

    eventoEditar() {

        document.addEventListener("click", async (e) => {

            const btn = e.target.closest(".btn-editar");

            if (!btn) return;

            await this.abrirModalEditar(
                Number(btn.dataset.id)
            );
        });
    }

    obtenerDatosFormulario() {
        try {
            const pelicula = document.getElementById("pelicula").value;
            const sala = document.getElementById("sala").value;
            const fechaInicio = document.getElementById("fechaInicio").value;
            const fechaFin = document.getElementById("fechaFin").value;
            const hora = document.getElementById("hora").value + ":00";
            const precio = document.getElementById("precio").value;

            return {
                pelicula_id: pelicula,
                sala_id: sala,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                hora,
                precio
            };
        } catch (error) {
            console.error(error);
        }
    }

    cargarDatosFormulario(programacion) {
        document.getElementById("programacion-id").value = programacion.id;
        document.getElementById("pelicula").value = programacion.pelicula_id;
        document.getElementById("sala").value = programacion.sala_id;
        document.getElementById("fechaInicio").value = programacion.fecha_inicio;
        document.getElementById("fechaFin").value = programacion.fecha_fin;
        document.getElementById("hora").value = programacion.hora.substring(0, 5);
        document.getElementById("precio").value = programacion.precio;
    }

    async abrirModalCrear() {

        this.limpiarFormulario();

        const peliculas = await this.modeloPelicula.obtenerPeliculas();
        const salas = await this.modeloSala.obtenerSalas();

        this.cargarSelectPeliculas(peliculas);
        this.cargarSelectSalas(salas);

        document.getElementById("modalTituloProgramacion").textContent = "Crear Programación";
    }

    async abrirModalEditar(id) {

        const programacion = await this.modelo.obtenerProgramacion(id);

        const peliculas = await this.modeloPelicula.obtenerPeliculas();

        const salas = await this.modeloSala.obtenerSalas();

        // 1. cargar options primero
        this.cargarSelectPeliculas(peliculas);
        this.cargarSelectSalas(salas);

        // 2. luego rellenar formulario
        this.cargarDatosFormulario(programacion);
        document.getElementById("modalTituloProgramacion").textContent = `Editar Programación ${id}`;

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById("modalProgramacion")
        ).show();
    }

    eventoEliminar() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(".btn-eliminar");

            if (!btn) return;

            const id = Number(btn.dataset.id);
            const confirmar = confirm("⚠️ Atención: eliminar es un efecto permanente ¿Deseas continuar?");
            
            if (!confirmar) return;
            
            try {
                await this.modelo.eliminarProgramacion(id);
                await this.mostrarProgramaciones();

            } catch (error) {
                console.error(error);
            }
        });
    }

    eventoCambiarEstado() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(".btn-estado");

            if (!btn) return;

            const id = Number(btn.dataset.id);
            const estadoActual = Number(btn.dataset.estado);
            const nuevoEstado = estadoActual === 1 ? 0 : 1;

            const confirmar = confirm("⚠️ Atención: al activar esta programación no podrá volver a borrador. ¿Deseas continuar?");

            if (!confirmar) return;

            try {
                await this.modelo.cambiarEstadoProgramacion(id, nuevoEstado);
                await this.mostrarProgramaciones();
            } catch (error) {
                console.error(error);
            }
        });
    }

    cargarSelectSalas(salas) {
        const select = document.getElementById("sala");
        select.innerHTML =
            '<option value="" disabled selected>Selecciona una sala</option>';

        salas.filter(s => Number(s.activa) === 1).forEach(sala => {
            const option = document.createElement('option');

            option.value = sala.id;
            option.textContent = `Sala ${sala.id}`
            select.appendChild(option);
        });


    }

    cargarSelectPeliculas(peliculas) {
        const select = document.getElementById('pelicula');
        select.innerHTML =
            '<option value="" disabled selected>Selecciona una película</option>';

        peliculas.filter(p => Number(p.disponible) === 1).forEach(pelicula => {

            const option = document.createElement('option');

            option.value = pelicula.id;
            option.textContent = `${pelicula.titulo}`;
            select.appendChild(option);
        }
        );
    }

    cerrarModal() {
        document.getElementById("modalProgramacion").addEventListener("hidden.bs.modal", () => {
            this.limpiarFormulario();
        });
    }

    limpiarFormulario() {
        document.getElementById("programacion-id").value = "";
        document.getElementById("formProgramacion").reset();

        document.getElementById("modalTituloProgramacion").textContent = "Crear Programación";
    }

}

export default ProgramaControlador;