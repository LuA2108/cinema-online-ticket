import DetallePelicula from "../../view/pelicula/DetallePelicula";
import ModeloPelicula from "../../models/modeloPelicula";
import ModeloImagen from "../../models/modeloImagen";
import CarouselTarjetas from "../../view/pelicula/CarouselTarjetas";
import ModeloProgramacion from "../../models/modeloProgramacion"
import ModeloFuncion from "../../models/modeloFuncion";
import ModeloGenero from "../../models/modeloGenero";

class DetallePeliculaControlador {

    constructor() {
        this.modeloPelicula = new ModeloPelicula();
        this.modeloImagen = new ModeloImagen();
        this.tarjeta = new CarouselTarjetas();
        this.modeloProgramacion = new ModeloProgramacion();
        this.modeloFuncion = new ModeloFuncion();
        this.detalle = new DetallePelicula();
        this.modeloGenero = new ModeloGenero();
    }

    async init() {
        this.renderizarPelicula();
        this.renderizarTarjetasPeliculas();
    }

    async renderizarPelicula() {
        const id = this.obtenerParametro();
        const pelicula = await this.modeloPelicula.obtenerPeliculaPorId(id);
        const imagenes = await this.modeloImagen.obtenerImagenPorPelicula(id);

        this.detalle.renderizarPelicula(pelicula);
        this.detalle.renderizarImagenes(imagenes);
    }

    async renderizarTarjetasPeliculas() {
        const peliculasRelacionadasConGenero = await this.obtenerPeliculaPorGenero();

        const peliculasRender = peliculasRelacionadasConGenero.map(pelicula => ({
            ...pelicula,
            poster: pelicula.imagenes?.find(i => i.tipo === "poster")?.url || ""
        }));
        

        this.tarjeta.renderizar(peliculasRender);

    }

    obtenerParametro() {
        const parametro = new URLSearchParams(window.location.search);
        return parametro.get("id");
    }

    async obtenerPeliculasDisponibles() {

        const peliculas = await this.modeloPelicula.obtenerPeliculas();
        const programaciones = await this.modeloProgramacion.obtenerProgramaciones()
        const funciones = await this.modeloFuncion.obtenerFuncionesPorEstado(2);

        // Programaciones disponibles
        const programacionesDisponibles = programaciones.filter(p => Number(p.estado) === 1);

        // IDs de programaciones con función activa
        const programacionesActivas = funciones.map(f => f.programacion_id);

        // Programaciones válidas
        const programacionesValidas = programacionesDisponibles.filter(p => programacionesActivas.includes(Number(p.id)));

        // IDs de películas disponibles
        const peliculasDisponiblesIds = programacionesValidas.map(p => p.pelicula_id);

        // Películas disponibles
        return peliculas.filter(pelicula => peliculasDisponiblesIds.includes(pelicula.id));
    }

    async obtenerPeliculaPorGenero() {
        const idPelicula =  Number(this.obtenerParametro());

        // Película actual (con géneros)
        const peliculaActual = await this.modeloPelicula.obtenerPeliculaCompletaPorId(idPelicula);
        const peliculasDisponibles = await this.obtenerPeliculasDisponibles();
        const generosActuales = peliculaActual.generos.map(g => Number(g.id));
        const relacionadas = [];

        for (const pelicula of peliculasDisponibles) {

            if (Number(pelicula.id) === idPelicula) {
                continue;
            }

            const peliculaCompleta = await this.modeloPelicula.obtenerPeliculaCompletaPorId(pelicula.id);
            const tieneGeneroComun = peliculaCompleta.generos.some(genero => generosActuales.includes(Number(genero.id)));

            if (tieneGeneroComun) {
                relacionadas.push(peliculaCompleta);
            }
        }
        return relacionadas;
    }

    async mostrarGeneros() {
        const generos = this.modeloGenero.obtenerGeneros();
        const datosPelicula = await this.modeloPelicula.obtenerPeliculasCompletas();


    }

}

export default DetallePeliculaControlador;