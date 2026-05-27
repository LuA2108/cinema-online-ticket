// Base de la API salas
const BASE_URL = 'http://localhost/cinema-online-ticket/api/salas';

// Obtener todas las salas
export async function obtenerSalas() {
    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al cargar las salas');
    }

    return data.datos;
}

// Obtener sala por ID
export async function obtenerSalaPorId(id) {
    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener la sala');
    }

    return data.datos;
}

// Obtener salas activas/inactivas
export async function obtenerSalasActivas(estado = true) {
    const res = await fetch(`${BASE_URL}/activas/${estado}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener salas activas');
    }

    return data.datos;
}

// Crear sala
export async function agregarSala(datos) {
    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al crear la sala');
    }

    return data.datos;
}

// Actualizar sala
export async function actualizarSala(id, datos) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al actualizar la sala');
    }

    return data.datos;
}

// Eliminar (desactivar) sala
export async function eliminarSala(id) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al eliminar la sala');
    }

    return data.datos;
}