import TablaView from "../componentes/tabla.js";
import { Boton } from "../componentes/boton.js";

class TablaUsuario {

    constructor() {
        this.tabla = new TablaView("tablaUsuariosBody");
    }

    renderizarTablaUsuarios(usuarios) {

        this.tabla.limpiar();

        usuarios.forEach(usuario => {

            const columnas = [
                usuario.id,
                usuario.rol_id == 1 ? "Admin" : "Usuario",
                usuario.nombre,
                usuario.email,
                usuario.ciudad || "-",
                usuario.provincia || "-",
                usuario.create_time
            ];

            const tr = this.tabla.crearFila(columnas);

            // Columna acciones
            const tdAcciones = document.createElement("td");

            const contenedorBotones = document.createElement("div");
            contenedorBotones.className = "d-flex gap-1";

            const btnEditar = Boton.crear(
                "Editar",
                "btn btn-warning btn-sm btn-editar",
                usuario.id
            );

            const btnEliminar = Boton.crear(
                "Eliminar",
                "btn btn-danger btn-sm btn-eliminar",
                usuario.id
            );

            contenedorBotones.append(btnEditar, btnEliminar);
            tdAcciones.appendChild(contenedorBotones);

            this.tabla.agregarCelda(tr, tdAcciones);

            this.tabla.agregarFila(tr);
        });
    }
}

export default TablaUsuario;