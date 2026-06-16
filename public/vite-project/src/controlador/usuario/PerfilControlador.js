import Perfil from "../../view/usuario/Perfil";
import ModeloUsuario from "../../models/modeloUsuario";

class PerfilControlador {
    constructor() {
        this.view = new Perfil();
        this.usuarioModelo = new ModeloUsuario();
    }

    async iniciar() {

        const token = localStorage.getItem("token");

        if (!token) {
            location.href = "../login.html";
            return;
        }

        const respuesta = await fetch(
            "http://localhost/cinema-online-ticket/api/perfil",
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        );

        const data = await respuesta.json();
        const idUsuario = data.usuario.id;
        const usuarioCompleto = await this.usuarioModelo.obtenerUsuarioPorId(idUsuario);

        console.log("datos:", usuarioCompleto);

        this.view.mostrarUsuario(usuarioCompleto);
        this.view.rellenarFormulario(usuarioCompleto);
        this.activarFormulario(idUsuario, token);
    }

    activarFormulario(idUsuario, token) {

        const form = document.getElementById("formEditarPerfil");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = {
                nombre: form.nombre.value,
                email: form.email.value,
                ciudad: form.ciudad.value,
                provincia: form.provincia.value,
            };

            if (form.contrasena.value.trim()) {
                datos.contrasena = form.contrasena.value;
            }

            try {
                await this.usuarioModelo.actualizarUsuario(idUsuario, datos);

                alert("Perfil actualizado correctamente");

                this.iniciar(); // recargar datos
            } catch (error) {
                console.error(error);
                alert(error.message);
            }
        });
    }
}

export default PerfilControlador;