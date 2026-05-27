import { obtenerImagenes } from "../api/imagenesApi";

class ModeloImagen{
    constructor(id, pelicula_id, tipo, url) {
        this.id = id;
        this.pelicula_id = pelicula_id;
        this.tipo = tipo;
        this.url = url;
    }

    async obtenerImagenes() {
        return await obtenerImagenes();
    }

}

export default ModeloImagen;