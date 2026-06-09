const BASE_URL = 'http://localhost/cinema-online-ticket/api';

/**
 * Obtener butacas por sala
 */
export async function obtenerButacasPorSala(salaId) {
    const res = await fetch(`${BASE_URL}/butacas/sala/${salaId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || "Error al obtener butacas de la sala");
    }

    return data.datos;
}

/**
 * Obtener mapa de butacas por función
 */
export async function obtenerMapaButacas(funcionId) {
    const res = await fetch(`${BASE_URL}/butacas/funcion/${funcionId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || "Error al obtener mapa de butacas");
    }

    return data.datos;
}

/**
 * Obtener butaca por ID
 */
export async function obtenerButacaPorId(id) {
    const res = await fetch(`${BASE_URL}/butacas/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || "Error al obtener butaca");
    }

    return data.datos;
}


