import {
    obtenerProductos,
    obtenerProductoPorId,
    obtenerProductosPorTipo,
    buscarProductos,
    agregarProducto,
    actualizarProducto,
    eliminarProducto
} from "../api/productosApi.js";

class ModeloProducto {

    constructor(
        id,
        nombre,
        precio,
        comentario,
        create_time,
        tipo
    ) {
        this.id = id;
        this.nombre = nombre;
        this.precio = precio;
        this.comentario = comentario;
        this.create_time = create_time;
        this.tipo = tipo;
    }

    async obtenerProductos() {
        return await obtenerProductos();
    }

    async obtenerProductoPorId(id) {
        return await obtenerProductoPorId(id);
    }

    async obtenerProductosPorTipo(tipoId) {
        return await obtenerProductosPorTipo(tipoId);
    }

    async buscarProductos(nombre) {
        return await buscarProductos(nombre);
    }

    async agregarProducto(datos) {
        return await agregarProducto(datos);
    }

    async actualizarProducto(id, datos) {
        return await actualizarProducto(id, datos);
    }

    async eliminarProducto(id) {
        return await eliminarProducto(id);
    }
}

export default ModeloProducto;