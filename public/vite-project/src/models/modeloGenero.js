import {obtenerGeneros} from "../api/generosApi";

class ModeloGenero {

    constructor(id, nombre) {
        this.id = id;
        this.nombre = nombre;
    }

    async obtenerGeneros() {
        return await obtenerGeneros();
    }
}

export default ModeloGenero;