class FuncionView {

    constructor() {
        //
    }

    renderizarTablaFunciones(funciones) {

        const tbody = document.getElementById('tablaFuncionesBody');

        tbody.innerHTML = '';

        funciones.forEach(funcion => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${funcion.id}</td>
                <td>${funcion.pelicula_id}</td>
                <td>${funcion.sala_id}</td>
                <td>${funcion.hora}</td>
                <td>${funcion.fecha_inicio}</td>
                <td>${funcion.fecha_fin}</td>
                <td>${funcion.estado_id}</td>
                <td>
                    <div class="d-flex  gap-1">
                        <button class="btn btn-warning btn-sm w-25 btn-editar" data-id="${funcion.id}">
                            Editar
                        </button>

                        <button class="btn btn-danger btn-sm w-250 btn-eliminar" data-id="${funcion.id}">
                            Eliminar
                        </button>

                    </div>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }
}

export default FuncionView;