import GeneroControlador from "../controlador/generoControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new GeneroControlador();
    await controlador.cargarGeneros();
});