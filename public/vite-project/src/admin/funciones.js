import FuncionControlador from "../controlador/funcionesControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new FuncionControlador();
    await controlador.cargarFunciones();
});