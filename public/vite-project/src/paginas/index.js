import CatalogoPeliculasControlador from "../controlador/pelicula/CatalogoPeliculasControlador.js";

document.addEventListener("DOMContentLoaded", () => {

    const controlador = new CatalogoPeliculasControlador();
    controlador.init(); 
});