import ModeloReserva from "../models/modelReserva";
import ModeloFuncion from "../models/modeloFuncion";
import ModeloProgramacion from "../models/modeloProgramacion";
import ModeloPelicula from "../models/modeloPelicula";
import TablaReservaUsuarios from "../view/reservas/tablaReservaUsuarios";
import ModeloSala from "../models/modeloSala";

class ListarReservasUsuarios {
    constructor() {
        this.modeloReserva = new ModeloReserva();
        this.modeloFuncion = new ModeloFuncion();
        this.modeloPelicula = new ModeloPelicula();
        this.modeloProgramacion = new ModeloProgramacion();
        this.modeloSala = new ModeloSala();

        this.vista = new TablaReservaUsuarios();
    }

    init() {
        this.cargarTabla()
    }

    async cargarTabla() {
        const usuario = JSON.parse(localStorage.getItem("usuario"));
        if (!usuario) return;

        const reservasUsuario = await this.modeloReserva.obtenerReservasPorUsuario(usuario.id);
        const programaciones = await this.modeloProgramacion.obtenerProgramaciones();
        const peliculas = await this.modeloPelicula.obtenerPeliculas();
        const funciones = await this.modeloFuncion.obtenerFunciones();
        const salas = await this.modeloSala.obtenerSalas();
        console.log("salas ",salas);

        const reservasCompletas = reservasUsuario.map(r => {
            
            const funcion = funciones.find(f => Number(f.id) === Number(r.funcion_id));
            console.log("Funcion ",funcion);

            const programacion = programaciones.find(p => p.id === funcion.programacion_id);
            console.log("programacion: ",programacion);

            const sala = salas.find(s => Number(s.id) == Number(programacion.sala_id));
            console.log("sala ", sala);

            const ubicacion = (r.butacas || [])
                .map(b => `Sala ${sala.id}`)
                .join(", ");

            return {
                ...r,
                funcion,
                programacion,
                sala,
                ubicacion
            };
        });
        
        console.log("Reserva formateada: ",reservasCompletas);
        this.vista.renderizarTabla(reservasCompletas);
    }

}


export default ListarReservasUsuarios;