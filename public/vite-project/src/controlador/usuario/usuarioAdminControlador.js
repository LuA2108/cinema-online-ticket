import ModeloUsuario from "../../models/modeloUsuario.js";
import TablaUsuario from "../../view/usuario/tablaUsuario.js";

class UsuarioAdminControlador {

    constructor() {
        this.modelo = new ModeloUsuario();
        this.vista = new TablaUsuario();
    }

    init() {

        this.cargarUsuarios();
        document.addEventListener("click", (e) => {

            // EDITAR
            if (e.target.classList.contains("btn-editar")) {
                const id = e.target.dataset.id;

                this.modelo.obtenerUsuarioPorId(id)
                    .then(usuario => this.abrirModalEditar(usuario))
                    .catch(err => console.error(err));
            }

            // ELIMINAR
            const btnEliminar = e.target.closest(".btn-eliminar");
            if (!btnEliminar) return;

            const usuarioId = btnEliminar.dataset.id;

            if (confirm("¿Eliminar usuario?")) {
                this.modelo.eliminarUsuario(usuarioId)
                    .then(() => this.cargarUsuarios())
                    .catch(err => console.error(err));
            }
        });

        const btnGuardar = document.getElementById("btnGuardarUsuario");

        if (btnGuardar) {
            btnGuardar.addEventListener("click", () => this.editarUsuario());
        }

    }


    async cargarUsuarios() {
        try {
            const usuarios = await this.modelo.obtenerUsuarios();
            this.vista.renderizarTablaUsuarios(usuarios);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    async editarUsuario() {

        try {
            const id = document.getElementById("edit-id").value;

            const datos = {
                rol_id: document.getElementById("edit-rol").value,
                nombre: document.getElementById("edit-nombre").value.trim(),
                email: document.getElementById("edit-email").value.trim(),
                ciudad: document.getElementById("edit-ciudad").value.trim(),
                provincia: document.getElementById("edit-provincia").value.trim()
            };

            await this.modelo.actualizarUsuario(id, datos);

            const modal = bootstrap.Modal.getInstance(
                document.getElementById("modalEditarUsuario")
            );

            modal.hide();
            await this.cargarUsuarios();
            alert("Usuario actualizado correctamente");

        } catch (error) {
            console.error(error);
            alert("Error al actualizar usuario");
        }
    }

    abrirModalEditar(usuario) {
        document.getElementById("edit-id").value = usuario.id;
        document.getElementById("edit-nombre").value = usuario.nombre;
        document.getElementById("edit-email").value = usuario.email;
        document.getElementById("edit-ciudad").value = usuario.ciudad || "";
        document.getElementById("edit-provincia").value = usuario.provincia || "";

        const modal = new bootstrap.Modal(
            document.getElementById("modalEditarUsuario")
        );

        modal.show();
    }
}

export default UsuarioAdminControlador;