import {obtenerUsuarios, obtenerUsuarioPorId, crearUsuario, actualizarUsuario, eliminarUsuario} from "../api/usuariosApi";

class ModeloUsuario {

    constructor(id, rol_id , nombre, email, ciudad, provincia, create_time) 
    {
        this.id = id;
        this.rol_id = rol_id;
        this.nombre = nombre;
        this.email = email;
        this.ciudad = ciudad;
        this.provincia = provincia;
        this.create_time = create_time;
    }

    async obtenerUsuarios() {
        return await obtenerUsuarios();
    }

    async obtenerUsuarioPorId(id) {
        return await obtenerUsuarioPorId(id);
    }

    async crearUsuario(datos) {
        return await crearUsuario(datos);
    }

    async actualizarUsuario(id, datos) {
        return await actualizarUsuario(id, datos);
    }

    async eliminarUsuario(id) {
        return await eliminarUsuario(id);
    }
}

export default ModeloUsuario;