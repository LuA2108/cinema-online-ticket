import SalaControlador from "../controlador/salaControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new SalaControlador();
    await controlador.cargarSalas();
});