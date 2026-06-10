// Base de la API salas
const BASE_URL = 'http://localhost/cinema-online-ticket/api/salas';

// Obtener todas las salas
export async function obtenerSalas() {
    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message || 'Error al cargar las salas');
    }

    return data.datos;
}

// Obtener sala por ID
export async function obtenerSalaPorId(id) {
    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message || 'Error al obtener la sala');
    }

    return data.datos;
}

// Obtener salas por estado
// GET /salas/estado/1
export async function obtenerSalasPorEstado(estado) {
    const res = await fetch(`${BASE_URL}/estado/${estado}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message || 'Error al obtener salas');
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
        throw new Error(data.error || data.message || 'Error al crear la sala');
    }

    return data.datos;
}

// Cambiar estado de sala
// PUT /salas/{id}/estado
export async function cambiarEstadoSala(id, estado) {
    const res = await fetch(`${BASE_URL}/${id}/estado`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estado })
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message || 'Error al cambiar estado de la sala');
    }

    return data.datos;
}