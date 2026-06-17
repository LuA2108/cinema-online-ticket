import VistaConfirmacionReserva from "../view/reservas/vistaConfirmacionReserva";
import ModeloReserva from "../models/modelReserva";
import ModeloReservaButaca from "../models/modeloReservaButaca";

class ConfirmacionReservaControlador {

    constructor() {
        this.vista = new VistaConfirmacionReserva();
        this.reservaModel = new ModeloReserva();
        this.reservaButacaModel = new ModeloReservaButaca();

        this.reserva = null;
        this.usuario = null;
    }

    // Inicializa la pantalla de confirmación
    async init() {
        this.cargarReserva();
        this.cargarUsuario();

        // Renderiza resumen de la reserva
        this.vista.renderizarResumen(this.reserva);

        // Evento de confirmación
        this.vista.bindConfirmar(() => {
            this.confirmarReserva();
        });
    }

    // 1. Cargar reserva desde sessionStorage
    cargarReserva() {
        const data = sessionStorage.getItem("reserva");

        if (!data) {
            alert("No hay reserva activa");
            window.location.href = "./index.html";
            return;
        }

        this.reserva = JSON.parse(data);
    }

    // 2. Cargar usuario desde localStorage (si existe)
    cargarUsuario() {
        this.usuario = JSON.parse(localStorage.getItem("usuario"));

        this.vista.renderizarUsuario(this.usuario);
        this.vista.rellenarFormulario(this.usuario);
    }

    // 3. Confirmar reserva en backend
    async confirmarReserva() {
        const esLogueado = !!this.usuario;
        const form = this.vista.obtenerFormulario();

        const nombre = esLogueado ? this.usuario.nombre : form.nombre;
        const email = esLogueado ? this.usuario.email : form.email;

        // Validaciones básicas
        if (!form.email) {
            alert("El email es obligatorio");
            return;
        }

        if (!this.reserva?.butacas?.length) {
            alert("No hay butacas seleccionadas");
            return;
        }

        try {
            // 1. Crear reserva principal
            const nuevaReserva = await this.reservaModel.agregarReserva({
                usuario_id: this.usuario?.id || null,
                nombre_cliente: nombre || null,
                email_cliente: email,
                funcion_id: this.reserva.funcion.id,
                estado_id: 2,
                total: this.calcularTotal()
            });

            // 2. Guardar butacas asociadas a la reserva
            for (const b of this.reserva.butacas) {
                await this.reservaButacaModel.agregarButaca({
                    butaca_id: b.id,
                    reserva_id: nuevaReserva.id
                });
            }

            // 3. Construir resumen final del ticket
            const resumen = {
                usuario: this.usuario || null,
                cliente: {
                    nombre: nombre || this.usuario?.nombre || null,
                    email: email
                },
                pelicula: this.reserva.pelicula,
                funcion: this.reserva.funcion,
                butacas: this.reserva.butacas,
                reserva_id: nuevaReserva.id,
                total: this.calcularTotal()
            };

            // Guardar resumen para la pantalla final
            sessionStorage.setItem("resumen_reserva", JSON.stringify(resumen));

            // Limpiar flujo anterior
            sessionStorage.removeItem("reserva");

            // Redirigir a comprobante
            window.location.href = "./comprobante.html";

        } catch (error) {
            console.error(error);
            alert("Error al crear la reserva");
        }
    }

    // 4. Calcular total de la compra
    calcularTotal() {
        return (this.reserva.butacas || []).reduce(
            (acc, b) => acc + Number(b.precio),
            0
        );
    }
}

export default ConfirmacionReservaControlador;