class SalaView {

    constructor() {
        //
    }

    renderizarTablaSalas(salas) {

        const tbody = document.getElementById('tablaSalasBody');

        tbody.innerHTML = '';

        salas.forEach(sala => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${sala.id}</td>
                <td>${sala.numero}</td>
                <td>${sala.capacidad}</td>
                <td>
                    ${sala.activa ? 'Sí' : 'No'}
                </td>
                <td>
                <div class="d-flex  gap-1">
                    <button class="btn btn-warning btn-sm w-25 btn-editar" data-id="${sala.id}">
                        Editar
                    </button>

                    <button class="btn btn-danger btn-sm w-25 btn-eliminar" data-id="${sala.id}">
                        Eliminar
                    </button>

                </div>
            </td>
            `;

            tbody.appendChild(tr);
        });
    }
}

export default SalaView;