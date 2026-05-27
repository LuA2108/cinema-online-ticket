import {
    obtenerSalas,
    obtenerSalaPorId,
    obtenerSalasActivas,
    agregarSala,
    actualizarSala,
    eliminarSala
} from "../api/salaApi.js";

class ModeloSala {

    constructor(id, numero, capacidad, activa) {
        this.id = id;
        this.numero = numero;
        this.capacidad = capacidad;
        this.activa = activa;
    }

    async obtenerSalas() {
        return await obtenerSalas();
    }

    async obtenerSalaPorId(id) {
        return await obtenerSalaPorId(id);
    }

    async obtenerSalasActivas(estado = true) {
        return await obtenerSalasActivas(estado);
    }

    async agregarSala(datos) {
        return await agregarSala(datos);
    }

    async actualizarSala(id, datos) {
        return await actualizarSala(id, datos);
    }

    async eliminarSala(id) {
        return await eliminarSala(id);
    }
}

export default ModeloSala;