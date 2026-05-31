import imagenAdminControlador from "../controlador/imagen/imagenAdminControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new imagenAdminControlador();
    controlador.init();
});