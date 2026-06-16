import AuthView from "../view/usuario/AuthView.js";
import AuthController from "../controlador/usuario/AuthController.js";

document.addEventListener("DOMContentLoaded", () => {
    const vista = new AuthView();
    const controlador = new AuthController(vista);

    controlador.iniciar();
});