import ModeloPelicula from "../../models/modeloPelicula.js";
import ModeloImagen from "../../models/modeloImagen.js";
import ModeloFuncion from "../../models/modeloFuncion.js";
import ModeloProgramacion from "../../models/modeloProgramacion.js";
import CarouselTarjetas from "../../view/pelicula/CarouselTarjetas.js";

class GaleriaPeliculas {

    constructor() {
        this.modeloPelicula = new ModeloPelicula();
        this.modeloImagen = new ModeloImagen();
        this.modeloFuncion = new ModeloFuncion();
        this.modeloProgramacion = new ModeloProgramacion();
        this.tarjetasCarousel = new CarouselTarjetas();
    }

    async init() {
        this.cargarGaleriaTarjetas();
    }

    async cargarGaleriaTarjetas() {
        const peliculasDisponibles = await this.obtenerPeliculasDisponibles();
        this.tarjetasCarousel.renderizar(peliculasDisponibles);
    }

    async obtenerPeliculasDisponibles() {
        const peliculas = await this.modeloPelicula.obtenerPeliculas();
        const imagenes = await this.modeloImagen.obtenerImagenes();
        const funciones = await this.modeloFuncion.obtenerFunciones();
        const programaciones = await this.modeloProgramacion.obtenerProgramaciones();

        const filtradas = peliculas.filter(p => Number(p.disponible) === 1 &&
            this.tieneFuncionActiva(p, programaciones, funciones)
        );

        return this.mapearImagen(filtradas, imagenes, "poster");
    }

    tieneFuncionActiva(pelicula, programaciones, funciones) {

        const programacionesPelicula = programaciones.filter(
            p => Number(p.pelicula_id) === Number(pelicula.id)
        );

        return programacionesPelicula.some(programacion =>
            funciones.some(funcion =>
                Number(funcion.programacion_id) === Number(programacion.id) &&
                funcion.estado === "activa"
            )
        );
    }

    mapearImagen(peliculas, imagenes, tipo) {

        return peliculas.map(p => {

            const img = imagenes.find(i =>
                Number(i.pelicula_id) === Number(p.id) &&
                i.tipo === tipo
            );

            return {
                ...p,
                banner: tipo === "banner"
                    ? (img ? `http://localhost/cinema-online-ticket${img.url}` : null)
                    : p.banner,

                poster: tipo === "poster"
                    ? (img ? `${img.url}` : null)
                    : p.poster
            };
        });
    }
}

export default GaleriaPeliculas;