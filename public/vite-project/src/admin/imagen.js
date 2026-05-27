import ImagenControlador from "../controlador/imagenControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new ImagenControlador();
    await controlador.cargarImagenes();
});