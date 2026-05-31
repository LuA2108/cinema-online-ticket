import TablaView from "../componentes/tabla.js";
import { Boton } from "../componentes/boton.js";

class ImagenTabla {

    constructor(tbodyId) {
        this.tabla = new TablaView("tablaImagenesBody");
    }

    renderizarTablaImagenes(imagenes) {

        // Limpiar tabla
        this.tabla.limpiar();

        imagenes.forEach(imagen => {

            // 🔹 Fila base
            const tr = this.tabla.crearFila([
                imagen.id,
                imagen.titulo_pelicula,
                imagen.tipo,
                imagen.url
            ]);

            // 🔹 Celda imagen preview
            const tdImg = document.createElement("td");

            const img = document.createElement("img");
            const BASE_URL = "http://localhost/cinema-online-ticket";
            img.src = BASE_URL + imagen.url;
            img.alt = "Imagen película";
            img.width = 120;

            tdImg.appendChild(img);

            // 🔹 Celda acciones
            const tdAcciones = document.createElement("td");

            const div = document.createElement("div");
            div.className = "d-flex gap-1";

            // 🔹 Botón Ver
            const btnVer = Boton.crear(
                "Ver",
                "btn btn-info btn-sm w-50 btn-ver",
                imagen.id
            );

            // 🔹 Botón Eliminar
            const btnEliminar = Boton.crear(
                "Eliminar",
                "btn btn-danger btn-sm w-50 btn-eliminar",
                imagen.id
            );

            div.append(btnVer, btnEliminar);
            tdAcciones.appendChild(div);

            // 🔹 Montaje final fila
            this.tabla.agregarCelda(tr, tdImg);
            this.tabla.agregarCelda(tr, tdAcciones);
            this.tabla.agregarFila(tr);
        });
    }
}

export default ImagenTabla;