// Base de la API funciones
const BASE_URL = 'http://localhost/cinema-online-ticket/api/funciones';

// Obtener todas las funciones
export async function obtenerFunciones() {

    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al cargar las funciones');
    }

    return data.datos;
}

// Obtener función por ID
export async function obtenerFuncionPorId(id) {

    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener la función');
    }

    return data.datos;
}

// Obtener funciones por estado
export async function obtenerFuncionesPorEstado(estadoId) {

    const res = await fetch(`${BASE_URL}/estado/${estadoId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener funciones por estado');
    }

    return data.datos;
}