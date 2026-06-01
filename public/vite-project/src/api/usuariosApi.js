// Se realiza fetch al backend de api peliculas

// Base de la api peliculas
const BASE_URL = 'http://localhost/cinema-online-ticket/api/usuarios';

// Obtener todas los usuarios
export async function obtenerUsuarios() {
    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cargar usuarios');
    }

    return data.datos;
}

// Obtener usuario por ID
export async function obtenerUsuarioPorId(id) {
    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener usuario');
    }

    return data.datos;
}

// Crear usuario
export async function crearUsuario(datos) {
    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al crear usuario');
    }

    return data.datos;
}

// Actualizar usuario
export async function actualizarUsuario(id, datos) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al actualizar usuario');
    }

    return data.datos;
}

// Eliminar usuario
export async function eliminarUsuario(id) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al eliminar usuario');
    }

    return data.datos;
}
