// Base de la API programaciones
const BASE_URL = 'http://localhost/cinema-online-ticket/api/programaciones';

// =========================
// Obtener todas
// =========================
export async function obtenerProgramaciones() {
    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message);
    }

    return data.datos;
}

// =========================
// Obtener por ID
// =========================
export async function obtenerProgramacionPorId(id) {
    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message);
    }

    return data.datos;
}

// =========================
// Obtener por película
// /programaciones/pelicula/1
// =========================
export async function obtenerProgramacionesPorPelicula(peliculaId) {
    const res = await fetch(`${BASE_URL}/pelicula/${peliculaId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message);
    }

    return data.datos;
}

// =========================
// Crear programación
// =========================
export async function crearProgramacion(datos) {
    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message);
    }

    return data.datos;
}

// =========================
// Actualizar programación
// =========================
export async function actualizarProgramacion(id, datos) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message);
    }

    return data.datos;
}

// =========================
// Cambiar estado
// /programaciones/1/estado
// =========================
export async function cambiarEstadoProgramacion(id, estado) {
    const res = await fetch(`${BASE_URL}/${id}/estado`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estado })
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al cambiar estado');
    }

    return data.datos;
}

// =========================
// Eliminar programación
// =========================
export async function eliminarProgramacion(id) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || data.message);
    }

    return data.datos;
}