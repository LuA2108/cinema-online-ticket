import SalaControlador from "../controlador/sala/salaAdminControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new SalaControlador();
    await controlador.init();
});