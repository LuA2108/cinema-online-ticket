import FuncionControlador from "../controlador/funcion/funcionesControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new FuncionControlador();
    await controlador.cargarFunciones();
});