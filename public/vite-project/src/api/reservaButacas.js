// URL base de la API del sistema de cine
const BASE_URL = 'http://localhost/cinema-online-ticket/api';

/**
 * Agrega una butaca a una reserva
 * @param {Object} datos - Información de la butaca y reserva
 */
export async function agregarButacaAReserva(datos) {
    const res = await fetch(`${BASE_URL}/reserva-butacas`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al agregar butaca');
    }

    return data.datos;
}

/**
 * Elimina una butaca de una reserva
 * @param {Object} datos - Datos de la butaca a eliminar
 */
export async function eliminarButacaDeReserva(datos) {
    const res = await fetch(`${BASE_URL}/reserva-butacas`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al eliminar butaca');
    }

    return data.datos;
}

/**
 * Obtiene todas las butacas asociadas a una reserva
 * @param {number} reservaId - ID de la reserva
 */
export async function obtenerButacasPorReserva(reservaId) {
    const res = await fetch(`${BASE_URL}/reserva-butacas/reserva/${reservaId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener butacas');
    }

    return data.datos;
}

/**
 * Obtiene las butacas ocupadas de una función específica
 * @param {number} funcionId - ID de la función
 */
export async function obtenerButacasPorFuncion(funcionId) {
    const res = await fetch(`${BASE_URL}/reserva-butacas/funcion/${funcionId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener butacas de la función');
    }

    return data.datos;
}