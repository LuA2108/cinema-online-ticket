import ProgramacionControlador from "../controlador/programacion/programacionControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new ProgramacionControlador();
    controlador.init();
});

