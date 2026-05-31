import { obtenerImagenes, obtenerImagenPorId, obtenerImagenPorPelicula, agregarImagen, eliminarImagen } from "../api/imagenesApi.js";

class ModeloImagen {
    constructor(id, pelicula_id, tipo, url) {
        this._id = id;
        this._pelicula_id = pelicula_id;
        this._tipo = tipo;
        this._url = url;
    }

    // GETTERS
    get id() {
        return this._id;
    }

    get pelicula_id() {
        return this._pelicula_id;
    }

    get tipo() {
        return this._tipo;
    }

    get url() {
        return this._url;
    }

    // SETTERS
    set id(valor) {
        this._id = valor;
    }

    set pelicula_id(valor) {
        this._pelicula_id = valor;
    }

    set tipo(valor) {
        this._tipo = valor;
    }

    set url(valor) {
        this._url = valor;
    }

    // MÉTODOS API
    // =====================

    async obtenerImagenes() {
        return await obtenerImagenes();
    }

    async obtenerImagenPorId(id) {
        return await obtenerImagenPorId(id);
    }

    async obtenerImagenPorPelicula(id_pelicula) {
        return await obtenerImagenPorPelicula(id_pelicula);
    }

    async agregarImagen(datos) {
        return await agregarImagen(datos);
    }

    async eliminarImagen(id) {
        return await eliminarImagen(id);
    }



}

export default ModeloImagen;