import ModeloUsuario from "../../models/modeloUsuario.js";

class RegistroControlador {
    constructor() {
        this.modelo = new ModeloUsuario();
    }

    async crearUsuario() {
        try {
            const usuario = {
                nombre: document.getElementById("name").value,
                email: document.getElementById("email").value,
                contrasena: document.getElementById("password").value,
                rol_id: 2,
                provincia: document.getElementById("provincia").value,
                ciudad: document.getElementById("ciudad").value
            };

            const resultado = await this.modelo.crearUsuario(usuario);
            alert("Usuario registrado correctamente");
            console.log(resultado);
            return resultado;
        } catch (error) {
            console.error(error);
            alert(error.message);
            throw error;
        }
    }
}

export default RegistroControlador;