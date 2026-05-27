class GeneroView {
    constructor() {
        //
    }

    renderizarTablaGeneros(generos) {
        const tbody = document.getElementById('tablaGenerosBody');
        tbody.innerHTML = "";

        generos.forEach(genero => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
            <td>${genero.id}</td>
            <td>${genero.nombre}</td>
            <td>
                <div class="d-flex  gap-1">
                    <button class="btn btn-warning btn-sm w-25 btn-editar" data-id="${genero.id}">
                        Editar
                    </button>

                    <button class="btn btn-danger btn-sm w-25 btn-eliminar" data-id="${genero.id}">
                        Eliminar
                    </button>

                </div>
            </td>
        `;

            tbody.appendChild(tr);
        });
    }
}

export default GeneroView;