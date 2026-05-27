import ModeloSala from "../models/modeloSala.js";
import SalaView from "../view/salaView.js";

class SalaControlador {

    constructor() {
        this.modelo = new ModeloSala();
        this.vista = new SalaView();
    }

    async cargarSalas() {

        try {

            const salas = await this.modelo.obtenerSalas();

            this.vista.renderizarTablaSalas(salas);

        } catch (error) {

            console.error(error);

            alert(error.message);
        }
    }
}

export default SalaControlador;