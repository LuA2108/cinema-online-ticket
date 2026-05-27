import ModeloProducto from "../models/modeloProducto.js";
import ProductoView from "../view/productoView.js";

class ProductoControlador {

    constructor() {
        this.modelo = new ModeloProducto();
        this.vista = new ProductoView();
    }

    async cargarProductos() {

        try {

            const productos = await this.modelo.obtenerProductos();

            this.vista.renderizarTablaProductos(productos);

        } catch (error) {

            console.error(error);

            alert(error.message);
        }
    }
}

export default ProductoControlador;