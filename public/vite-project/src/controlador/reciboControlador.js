import jsPDF from "jspdf";
import html2canvas from "html2canvas";
import ComprobanteReserva from "../view/reservas/comprobanteReserva";

class ReciboControlador {

    constructor() {
        // Instancia de la vista encargada de mostrar el comprobante
        this.vista = new ComprobanteReserva();

        // Aquí se guardará la reserva recuperada del sessionStorage
        this.reserva = null;
    }

    // Punto de entrada del controlador
    init() {
        // Cargar datos del resumen de la reserva desde sessionStorage
        this.cargarReserva();

        // Renderizar toda la información del comprobante en pantalla
        this.vista.renderizar(this.reserva);

        // Asignar evento al botón de descarga del PDF
        this.vista.bindDescargarPDF(() => {
            this.generarPDF();
        });
    }

    // Recupera la información de la reserva guardada en el navegador
    cargarReserva() {
        const datos = sessionStorage.getItem("resumen_reserva");

        // Debug (ojo: aquí parece haber un error, se loguea "this.datos" en vez de "datos")
        console.log(this.datos);

        // Si no existe información, no se puede continuar
        if (!datos) {
            console.log("No hay datos de la reserva ");
            return;
        }

        // Convertimos el JSON a objeto usable en JS
        this.reserva = JSON.parse(datos);

        console.log("Resumen reserva:", this.reserva);

        // Normalización de datos:
        // Evita errores si alguna propiedad viene null o undefined
        this.reserva.cliente ??= {};
        this.reserva.cliente.nombre ??= "";
        this.reserva.cliente.email ??= "";
        this.reserva.butacas ??= [];
        this.reserva.total ??= 0;

        return true;
    }

    async generarPDF() {
        // Selecciona el elemento HTML que contiene el ticket/comprobante
        const ticket = document.querySelector(".recibo-card");

        // Convierte el HTML del ticket en una imagen usando canvas
        const canvas = await html2canvas(ticket);

        // Transforma el canvas en una imagen en formato PNG
        const imgData = canvas.toDataURL("image/png");

        // Crea un documento PDF (formato básico A4 por defecto)
        const pdf = new jsPDF();

        // Inserta la imagen del ticket dentro del PDF
        // (10,10 = margen, 190 = ancho aproximado de página)
        pdf.addImage(imgData, "PNG", 10, 10, 190, 0);

        // Descarga el archivo PDF con el id de la reserva
        pdf.save(`reserva-${this.reserva.reserva_id}.pdf`);
    }
}

export default ReciboControlador;