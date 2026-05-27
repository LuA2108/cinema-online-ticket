import { obtenerUsuarios } from "../api/usuariosApi";

class ModeloUsuario {
    #contrasena;

    constructor(id, rol_id, nombre, email, contrasena, ciudad, provincia, create_time) {

        this.id = id;
        this.rol_id = rol_id;
        this.nombre = nombre;
        this.email = email;
        this.contrasena = contrasena;
        this.ciudad = ciudad;
        this.provincia = provincia;
        this.create_time = create_time;
    }

    async obtenerUsuarios($datos) {
        return await obtenerUsuarios();
    }

}

export default ModeloUsuario;