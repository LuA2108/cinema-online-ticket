import { getPeliculas, obtenerPeliculasCompletas, crearPelicula, obtenerPeliculaPorId, actualizarPelicula, activarPelicula, desactivarPelicula } from "../api/peliculaApi.js";

class ModeloPelicula {

    constructor(id, titulo, descripcion, director, anio, duracion, precio, disponible, create_time) 
    {
        this._id = id;
        this._titulo = titulo;
        this._descripcion = descripcion;
        this._director = director;
        this._anio = anio;
        this._duracion = duracion;
        this._precio = precio;
        this._disponible = disponible;
        this._create_time = create_time;
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

    // PRECIO
    get precio() {
        return this._precio;
    }

    set precio(value) {
        this._precio = value;
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
}

export default ModeloPelicula;