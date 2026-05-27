import ModeloUsuario from "../models/modeloUsuario";
import UsuarioView from "../view/usuarioView";

class UsuarioControlador {

    constructor() {
        this.modelo = new ModeloUsuario();
        this.vista = new UsuarioView();
    }

    async cargarUsuarios() {
        try {
            const usuarios = await this.modelo.obtenerUsuarios();
            this.vista.renderizarTablaUsuarios(usuarios);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
}

export default UsuarioControlador;