import ReservaControlador from "../controlador/reservaControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new ReservaControlador();
    await controlador.cargarReservas();
});