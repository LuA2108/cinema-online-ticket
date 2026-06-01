import UsuarioAdminControlador from "../controlador/usuario/usuarioAdminControlador.js";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new UsuarioAdminControlador();
    await controlador.init();
});