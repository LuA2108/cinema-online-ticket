import UsuarioControlador from "../controlador/usuarioControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new UsuarioControlador();
    await controlador.cargarUsuarios();
});