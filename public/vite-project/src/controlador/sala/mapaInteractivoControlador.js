import MapaButacasInteractivo from "../../view/butacas/mapaButacasInteractivo";
import Programacion from "../../models/modeloProgramacion";
import ModeloFuncion from "../../models/modeloFuncion";
import ReservaButaca from "../../models/modeloReservaButaca";
import ModeloButaca from "../../models/modeloButaca";
import ModeloProgramacion from "../../models/modeloProgramacion";

class MapaInteractivoControlador {

    constructor() {
        this.vista = new MapaButacasInteractivo();
        this.butacaModel = new ModeloButaca();
        this.reservaButacaModel = new ReservaButaca();
        this.modeloFuncion = new ModeloFuncion();
        this.programacion = new ModeloProgramacion();

        this.seleccionadas = [];
        this.precioBase = 0;
    }

    async init() {
        // 1. Recuperar reserva temporal
        const reserva = JSON.parse(sessionStorage.getItem("reserva"));

        if (!reserva?.funcion) {
            window.location.href = "./funciones.html";
            return;
        }
        console.log("Reserva: ", reserva);

        const funcion = reserva.funcion;
        console.log("Funcion", funcion);

        // 2. Obtener butacas de la sala
        const butacas = await this.butacaModel.obtenerButacasPorSala(funcion.sala_id);
        console.log("Butacas: ", butacas);

        // 3. Obtener butacas ocupadas
        const reservas = await this.reservaButacaModel.obtenerPorFuncion(funcion.id);
        console.log("Reservas ", reservas);

        const ocupadas = reservas.map(r => Number(r.butaca_id));
        console.log("Ocupadas", ocupadas);

        // 4. Renderizar mapa
        this.vista.renderizarMapa(butacas, ocupadas);

        // 5. Precio de la función
        this.precioBase = await this.obtenerPrecio(funcion.id);
        console.log("precio base:", this.precioBase);

        // 6. UI sala
        const salaEl = document.getElementById("sala");
        if (salaEl) {
            salaEl.textContent = `Sala ${funcion.sala_id}`;
        }

        // 7. Eventos
        this.seleccionarButaca();
        this.eventoConfirmar();
    }

    seleccionarButaca() {
        const butacas = document.querySelectorAll(".butaca");

        butacas.forEach(boton => {
            boton.addEventListener("click", () => {

                if (boton.classList.contains("ocupada")) return;

                const id = Number(boton.dataset.id);
                boton.classList.toggle("seleccionada");
                const existe = this.seleccionadas.find(b => b.id === id);

                if (!existe) {
                    this.seleccionadas.push({
                        id,
                        fila: boton.dataset.fila,
                        numero: boton.dataset.numero,
                        precio: this.precioBase
                    });

                } else {
                    this.seleccionadas = this.seleccionadas.filter(b => b.id !== id);
                }

                // Guardar reserva despues de seleccionarlas
                this.guardarReserva();

                this.vista.renderResumen(this.seleccionadas, this.calcularTotal());
            });
        });
    }

    async guardarReserva() {
        const reserva = JSON.parse(sessionStorage.getItem("reserva")) || {};
        reserva.butacas = this.seleccionadas;

        sessionStorage.setItem("reserva", JSON.stringify(reserva));
        console.log("SessionStorage reserva:", reserva.butacas);
    }

    calcularTotal() {
        return this.seleccionadas.length * this.precioBase;
    }

    async obtenerPrecio(funcionId) {
        const funcion = await this.modeloFuncion.obtenerFuncionPorId(funcionId);
        const programacion = await this.programacion.obtenerProgramacion(funcion.programacion_id);

        return Number(programacion.precio);
    }

    eventoConfirmar() {
        const btn = document.querySelector(".btn-confirmar");
        if (!btn) return;

        btn.addEventListener("click", () => {
            const reserva = JSON.parse(sessionStorage.getItem("reserva"));

            // validación básica
            if (!reserva || reserva.butacas.length === 0) {
                alert("Selecciona al menos una butaca");
                return;
            }

            reserva.butacas = this.seleccionadas;
            sessionStorage.setItem("reserva", JSON.stringify(reserva));
            console.log("Reserva final:", reserva);

            // Se redirige a la siguiente página
            window.location.href = "./confirmar.html";
        });
    }
}

export default MapaInteractivoControlador;