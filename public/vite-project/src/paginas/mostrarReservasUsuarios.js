import ListarReservasUsuario from "../controlador/listarReservasUsuario";

document.addEventListener("DOMContentLoaded", () => {

    const controlador = new ListarReservasUsuario();
    controlador.init(); 
});