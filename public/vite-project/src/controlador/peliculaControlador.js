import ModeloPelicula from "../models/modeloPelicula.js";
import PeliculaView from "../view/peliculaView.js";

class PeliculaControlador {

    constructor() {
        this.modelo = new ModeloPelicula();
        this.vista = new PeliculaView();
    }

    async cargarPeliculas() {

        try {

            const peliculas = await this.modelo.obtenerPeliculas();

            this.vista.renderizarTablaPeliculas(peliculas);

        } catch (error) {

            console.error(error);

            alert(error.message);
        }
    }
}

export default PeliculaControlador;