import ReservaFuncion from "../../view/funcion/ReservaFuncion";
import ModeloFuncion from "../../models/modeloFuncion";
import ModeloPelicula from "../../models/modeloPelicula";
import ModeloImagen from "../../models/modeloImagen";
import ModeloProgramacion from "../../models/modeloProgramacion";

class SeleccionFuncionControlador {
    constructor() {
        this.funcion = new ModeloFuncion();
        this.pelicula = new ModeloPelicula();
        this.programacion = new ModeloProgramacion();
        this.imagen = new ModeloImagen();
        this.vista = new ReservaFuncion();
    }

    async init() {
        const peliculaId = this.obtenerParametro();
        const imagenPelicula = await this.obtenerImagen();
        console.log(peliculaId);

        const pelicula = await this.pelicula.obtenerPeliculaPorId(peliculaId);
        console.log("Pelicula", pelicula);

        const reserva = JSON.parse(sessionStorage.getItem("reserva")) || {};
        reserva.pelicula = { id: pelicula.id, titulo: pelicula.titulo };
        sessionStorage.setItem("reserva", JSON.stringify(reserva));

        const programaciones = await this.programacion.obtenerProgramacionesPorPelicula(peliculaId);
        console.log("programaciones", programaciones);

        const programacionMap = {};

        programaciones.forEach(p => {
            programacionMap[p.id] = p;
        });

        console.log("Programacion map: " + programacionMap);

        const funciones = await this.funcion.obtenerFunciones();
        console.log("funciones: ", funciones);

        const idsProgramacion = programaciones.map(p => Number(p.id));
        console.log("Ids programaciones", idsProgramacion);

        const funcionesFiltradas = funciones
            .filter(f =>
                f.estado == 'activa' &&
                idsProgramacion.includes(Number(f.programacion_id))
            )
            .map(f => {
                const prog = programacionMap[Number(f.programacion_id)];

                return {
                    ...f,
                    sala: prog?.sala_id //
                };
            });;
        console.log("funciones filtradas: ", funcionesFiltradas);

        this.vista.renderizarTituloPelicula(pelicula);
        this.vista.renderizarPoster(imagenPelicula[0].url);
        this.vista.renderizarFunciones(funcionesFiltradas);

        this.seleccionarFuncion();
    }

    async obtenerImagen() {
        const peliculaID = this.obtenerParametro();
        return this.imagen.obtenerImagenPorPelicula(peliculaID);
    }

    seleccionarFuncion() {
        document.querySelector(".funcion-info")
            .addEventListener("click", async (e) => {

                const btn = e.target.closest(".hora-btn");

                if (btn) {
                    const idFuncion = btn.dataset.id;
                    console.log("Seleccionada: ", idFuncion);

                    // reconstruyes la función
                    const reserva = JSON.parse(sessionStorage.getItem("reserva")) || {};
                    const funcion = await this.funcion.obtenerFuncionPorId(idFuncion);

                    reserva.funcion = funcion;
                    reserva.butacas = [];

                    sessionStorage.setItem("reserva", JSON.stringify(reserva));
                    console.log("Reserva", reserva);


                    window.location.href = `./seleccionar_butaca.html`;
                }
            });
    }

    seleccionHoras() {
        document.querySelectorAll('.hora-btn').forEach(btn => {

            btn.addEventListener('click', () => {

                // quitar activo de todos los botones de esa función
                document.querySelectorAll('.hora-btn')
                    .forEach(b => b.classList.remove('active'));

                // activar el seleccionado
                btn.classList.add('active');

                // opcional: guardar ID
                console.log("Función seleccionada ID:", btn.dataset.id);
            });

        });
    }

    obtenerParametro() {
        const parametro = new URLSearchParams(window.location.search);
        return parametro.get("id");
    }
}

export default SeleccionFuncionControlador;