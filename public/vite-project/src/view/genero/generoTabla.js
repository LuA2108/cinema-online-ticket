    import TablaView from "../componentes/tabla.js";
    import { Boton } from "../componentes/boton.js";

    export default function renderizarTablaGeneros(generos) {

        //  Creamos la instancia de la tabla apuntando al tbody
        const tabla = new TablaView("tablaGenerosBody");
        const boton = new Boton();
        // Limpiamos la tabla antes de renderizar
        tabla.limpiar();

        // Recorremos todos los géneros
        generos.forEach(genero => {

            // 🔹 FILA DE LA TABLA

            // Creamos la fila usando el método de TablaView
            const tr = tabla.crearFila([
                genero.id,
                genero.nombre
            ]);

            // 🔹 CELDA DE ACCIONES
            // Creamos la celda donde irán los botones
            const tdAcciones = document.createElement("td");

            // 🔹 CONTENEDOR DE BOTONES

            // Div para aplicar estilos (flex + separación)
            const div = document.createElement("div");
            div.className = "d-flex gap-1";

            // 🔹 BOTÓN ELIMINAR
            const btnEliminar = Boton.crear(
                "Eliminar",
                "btn btn-danger btn-sm w-25 btn-eliminar",
                genero.id
            );

            // 🔹 ARMADO FINAL
            div.append(btnEliminar);
            tdAcciones.appendChild(div);
            tabla.agregarCelda(tr, tdAcciones);
            tabla.agregarFila(tr);
        });

        
    }