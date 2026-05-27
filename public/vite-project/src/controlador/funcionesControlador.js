import ModeloFuncion from "../models/modeloFuncion.js";
import FuncionView from "../view/funcionView.js";

class FuncionControlador {

    constructor() {
        this.modelo = new ModeloFuncion();
        this.vista = new FuncionView();
    }

    async cargarFunciones() {

        try {

            const funciones = await this.modelo.obtenerFunciones();

            this.vista.renderizarTablaFunciones(funciones);

        } catch (error) {

            console.error(error);

            alert(error.message);
        }
    }
}

export default FuncionControlador;