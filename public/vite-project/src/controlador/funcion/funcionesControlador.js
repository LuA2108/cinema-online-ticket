import ModeloFuncion from "../../models/modeloFuncion.js";
import TablaFuncion from "../../view/funcion/TablaFuncion.js";

class FuncionControlador {

    constructor() {
        this.modelo = new ModeloFuncion();
        this.vista = new TablaFuncion();
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