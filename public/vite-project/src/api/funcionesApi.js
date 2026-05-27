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

// Obtener funciones por película
export async function obtenerFuncionesPorPelicula(peliculaId) {

    const res = await fetch(`${BASE_URL}/pelicula/${peliculaId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener funciones por película');
    }

    return data.datos;
}

// Obtener todos los estados
export async function obtenerEstadosFuncion() {

    const res = await fetch(`${BASE_URL}/estados`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener estados');
    }

    return data.datos;
}

// Obtener estado específico
export async function obtenerEstadoFuncion(id) {

    const res = await fetch(`${BASE_URL}/estado-id/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al obtener estado');
    }

    return data.datos;
}

// Crear función
export async function agregarFuncion(datos) {

    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al crear la función');
    }

    return data.datos;
}

// Actualizar función
export async function actualizarFuncion(id, datos) {

    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al actualizar la función');
    }

    return data.datos;
}

// Eliminar función
export async function eliminarFuncion(id) {

    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.message || 'Error al eliminar la función');
    }

    return data.datos;
}