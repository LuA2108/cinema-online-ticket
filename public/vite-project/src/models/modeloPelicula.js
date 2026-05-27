import { getPeliculas } from "../api/peliculaApi";

class ModeloPelicula {
    constructor(id, titulo, descripcion, director, anio, duracion, precio, disponible, create_time) {
        this.id = id;
        this.titulo = titulo;
        this.descripcion = descripcion;
        this.director = director;
        this.anio = anio;
        this.duracion = duracion;
        this.precio = precio;
        this.disponible = disponible;
        this.create_time = create_time;
    }

    async obtenerPeliculas() {
        return await getPeliculas();
    }

    

}

export default ModeloPelicula;