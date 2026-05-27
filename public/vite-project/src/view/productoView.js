class ProductoView {

    constructor() {
        //
    }

    renderizarTablaProductos(productos) {

        const tbody = document.getElementById('tablaProductosBody');

        tbody.innerHTML = '';

        productos.forEach(producto => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${producto.id}</td>
                <td>${producto.nombre}</td>
                <td>${producto.precio} €</td>
                <td>${producto.comentario ?? ''}</td>
                <td>${producto.create_time}</td>
                <td>${producto.tipo}</td>
                <td>
                    <div class="d-flex  gap-1">
                        <button class="btn btn-warning btn-sm w-25 btn-editar" data-id="${producto.id}">
                            Editar
                        </button>

                        <button class="btn btn-danger btn-sm w-50 btn-eliminar" data-id="${producto.id}">
                            Eliminar
                        </button>

                    </div>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }
}

export default ProductoView;