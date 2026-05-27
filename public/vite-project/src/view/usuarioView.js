class UsuarioView {
    constructor() {
        //
    }

    renderizarTablaUsuarios(usuarios) {

        const tbody = document.getElementById('tablaUsuariosBody');
        tbody.innerHTML = '';

        usuarios.forEach(usuario => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${usuario.id}</td>
                <td>
                    ${(usuario.rol_id === 1) ? 'Admin' : 'Usuario'}
                </td>
                <td>${usuario.nombre}</td>
                <td>${usuario.email}</td>
                <td>${usuario.contrasena}</td>
                <td>
                    ${(usuario.ciudad) ? usuario.ciudad : '-' }
                </td>
                <td>
                    ${(usuario.provincia) ? usuario.provincia : '-' }
                </td>
                <td>${usuario.create_time}</td>`;

            tbody.appendChild(tr);
        });
    }
}

export default UsuarioView;