import ProductoControlador from "../controlador/productoControlador";

document.addEventListener('DOMContentLoaded', async () => {
    const controlador = new ProductoControlador();
    await controlador.cargarProductos();
});