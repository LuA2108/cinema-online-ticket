import ModeloImagen from "../models/modeloImagen";
import ImagenView from "../view/imagenView";

class ImagenControlador {

    constructor() {
        this.modelo = new ModeloImagen();
        this.vista = new ImagenView();
    }

    async cargarImagenes() {
        try {
            const imagenes = await this.modelo.obtenerImagenes();
            this.vista.renderizarTablaImagenes(imagenes);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
}

export default ImagenControlador;