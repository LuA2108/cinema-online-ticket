class ImagenView {
    constructor() {
        //
    }

    renderizarTablaImagenes(imagenes) {

        const tbody = document.getElementById('tablaImagenesBody');
        tbody.innerHTML = '';

        imagenes.forEach(imagen => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td> ${imagen.id}</ >
                <td>${imagen.pelicula_id}</td>
                <td>${imagen.tipo}</td>
                <td>${imagen.url}</td>
                <td>
                    <img src="${imagen.url}" alt="Imagen película" width="150">
                </td>
                `

            tbody.appendChild(tr);
            console.log(imagen.url);
        });
    }
}

export default ImagenView;