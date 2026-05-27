class ReservaView {

    constructor() {
        //
    }

    renderizarTablaReservas(reservas) {

        const tbody = document.getElementById('tablaReservasBody');

        tbody.innerHTML = '';

        reservas.forEach(reserva => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${reserva.id}</td>
                <td>${reserva.usuario_id ?? 'Invitado'}</td>
                <td>${reserva.nombre_cliente}</td>
                <td>${reserva.email_cliente}</td>
                <td>${reserva.funcion_id}</td>
                <td>${reserva.estado_id}</td>
                <td>${reserva.fecha_reserva}</td>
                <td>${reserva.total} €</td>
            `;

            tbody.appendChild(tr);
        });
    }
}

export default ReservaView;