import PeliculaControlador from "../controlador/peliculaControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new PeliculaControlador();
    await controlador.cargarPeliculas();
});