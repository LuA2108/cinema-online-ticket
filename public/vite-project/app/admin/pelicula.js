import PeliculaController from "cinema-online-ticket/public/vite-proyect/src/controller/pelicula";
import PeliculaTabla from "cinema-online-ticket/public/vite-proyect/src/view/peliculas/PeliculaTabla.js";

const controller = new PeliculaController(
    new PeliculaTabla("tablaPeliculas")
);

controller.init();