import { getPeliculas, obtenerPeliculasCompletas, crearPelicula, obtenerPeliculaPorId, actualizarPelicula, activarPelicula, desactivarPelicula, obtenerPeliculaCompletaPorId } from "../api/peliculaApi.js";

class ModeloPelicula {

    constructor(id, titulo, descripcion, director, anio, duracion, disponible, destacado, create_time) {
        this.id = id;
        this.titulo = titulo;
        this.descripcion = descripcion;
        this.director = director;
        this.anio = anio;
        this.duracion = duracion;
        this.disponible = disponible;
        this.destacado = destacado;
        this.create_time = create_time;
    }

    // MÉTODOS API
    async obtenerPeliculas() {
        return await getPeliculas();
    }

    async obtenerPeliculasCompletas() {
        return await obtenerPeliculasCompletas();
    }

    async obtenerPeliculaPorId(pelicula_id) {
        return await obtenerPeliculaPorId(pelicula_id);
    }

    async crearPelicula(pelicula_datos) {
        return await crearPelicula(pelicula_datos);
    }

    async actualizarPelicula(id, pelicula_datos) {
        return await actualizarPelicula(id, pelicula_datos);
    }

    async activarPelicula(id) {
        return await activarPelicula(id);
    }

    async desactivarPelicula(id) {
        return await desactivarPelicula(id);
    }

    async obtenerPeliculaCompletaPorId(id) {
        return await obtenerPeliculaCompletaPorId(id);
    }

    // =========================
    // GETTERS Y SETTERS
    // =========================

    // ID
    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    // TITULO
    get titulo() {
        return this._titulo;
    }

    set titulo(value) {
        this._titulo = value;
    }

    // DESCRIPCION
    get descripcion() {
        return this._descripcion;
    }

    set descripcion(value) {
        this._descripcion = value;
    }

    // DIRECTOR
    get director() {
        return this._director;
    }

    set director(value) {
        this._director = value;
    }

    // AÑO
    get anio() {
        return this._anio;
    }

    set anio(value) {
        this._anio = value;
    }

    // DURACION
    get duracion() {
        return this._duracion;
    }

    set duracion(value) {
        this._duracion = value;
    }

    // DISPONIBLE
    get disponible() {
        return this._disponible;
    }

    set disponible(value) {
        this._disponible = value;
    }

    // CREATE TIME
    get create_time() {
        return this._create_time;
    }

    set create_time(value) {
        this._create_time = value;
    }

    // DESTACADO
    get destacado() {
        return this._destacado;
    }

    set destacado(value) {
        this._destacado = value;
    }
}

export default ModeloPelicula;