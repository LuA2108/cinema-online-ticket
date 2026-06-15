import GaleriaPeliculas from "../controlador/pelicula/GaleriaPeliculas";

document.addEventListener("DOMContentLoaded", () => {

    const controlador = new GaleriaPeliculas();
    controlador.init(); 
});