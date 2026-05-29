import { obtenerGeneros, agregarGenero, eliminarGenero, obtenerGenero } from "../api/generosApi.js";
class ModeloGenero {

    constructor(id, nombre) {
        this._id = id;
        this._nombre = nombre;
    }

    // GETTERS
    get id() {
        return this._id;
    }

    get nombre() {
        return this._nombre;
    }

    // SETTERS
    set id(valor) {
        this._id = valor;
    }

    set nombre(valor) {
        this._nombre = valor;
    }

    // MÉTODOS API
    async obtenerGeneros() {
        return await obtenerGeneros();
    }

    async agregarGenero(nombre) {
        return await agregarGenero(nombre);
    }

    async eliminarGenero(id) {
        return await eliminarGenero(id);
    }

    async obtenerGenero(id) {
        return await obtenerGenero(id);
    }
}

export default ModeloGenero;