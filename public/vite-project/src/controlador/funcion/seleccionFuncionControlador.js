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

        const pelicula = await this.pelicula.obtenerPeliculaPorId(peliculaId);
        console.log("Pelicula",pelicula);

        const programaciones = await this.programacion.obtenerProgramacionesPorPelicula(peliculaId);
        console.log("programaciones" ,programaciones);

        const funciones = await this.funcion.obtenerFunciones();
        console.log("funciones: ",funciones);

        const idsProgramacion = programaciones.map(p => Number(p.id));
        console.log("Ids programaciones", idsProgramacion);

        const funcionesFiltradas =
        funciones.filter(f => f.estado == 'activa' &&
            idsProgramacion.includes(
                Number(f.programacion_id)
            )
        );
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
            .addEventListener("click", (e) => {

                if (e.target.classList.contains("hora-btn")) {

                    const idFuncion = e.target.dataset.id;
                    console.log("Seleccionada:", idFuncion);

                    // aquí se navega a butacas
                    window.location.href = `./seleccionar_butaca.html?id=${idFuncion}`;
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