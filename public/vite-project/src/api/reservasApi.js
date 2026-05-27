// Base API reservas
const BASE_URL = 'http://localhost/cinema-online-ticket/api/reservas';

// Obtener todas las reservas
export async function obtenerReservas() {

    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cargar reservas');
    }

    return data.datos;
}

// Obtener reserva por ID
export async function obtenerReservaPorId(id) {

    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener reserva');
    }

    return data.datos;
}

// Obtener reservas por usuario
export async function obtenerReservasPorUsuario(usuarioId) {

    const res = await fetch(`${BASE_URL}/usuario/${usuarioId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener reservas del usuario');
    }

    return data.datos;
}

// Obtener reservas por función
export async function obtenerReservasPorFuncion(funcionId) {

    const res = await fetch(`${BASE_URL}/funcion/${funcionId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener reservas de la función');
    }

    return data.datos;
}

// Crear reserva
export async function agregarReserva(datos) {

    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al crear reserva');
    }

    return data.datos;
}

// Actualizar reserva
export async function actualizarReserva(id, datos) {

    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al actualizar reserva');
    }

    return data.datos;
}

// Cambiar estado reserva
export async function cambiarEstadoReserva(id, estado_id) {

    const res = await fetch(`${BASE_URL}/${id}/estado`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estado_id })
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cambiar estado');
    }

    return data.datos;
}

// Eliminar reserva
export async function eliminarReserva(id) {

    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al eliminar reserva');
    }

    return data.datos;
}