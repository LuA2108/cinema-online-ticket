import GeneroControlador from "../controlador/genero/generoAdminControlador.js";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new GeneroControlador();
    controlador.init();
});