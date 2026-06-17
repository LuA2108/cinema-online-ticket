import MapaInteractivoControlador from "../controlador/sala/mapaInteractivoControlador";

document.addEventListener("DOMContentLoaded", () => {
    const controlador = new MapaInteractivoControlador();
    controlador.init(); 
});