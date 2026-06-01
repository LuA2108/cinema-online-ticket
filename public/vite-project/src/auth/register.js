import RegristroControlador from "../controlador/usuario/registroControlador.js";

const controlador = new RegristroControlador();

document.getElementById("form-register").addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
        const resultado = await controlador.crearUsuario();

        if (resultado) {
            window.location.href = "/login.html";
        }
    }catch (error) {
    alert("Error al registrar usuario");
}
});