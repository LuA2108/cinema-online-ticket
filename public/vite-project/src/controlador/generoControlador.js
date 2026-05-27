import ModeloGenero from "../models/modeloGenero";
import GeneroView from "../view/generoView";

class GenerosControlador {
    constructor() {
        this.modelo = new ModeloGenero();
        this.vista = new GeneroView();
    }

    async cargarGeneros() {
        try {
            const imagenes = await this.modelo.obtenerGeneros();
            this.vista.renderizarTablaGeneros(imagenes);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
}

export default GenerosControlador;