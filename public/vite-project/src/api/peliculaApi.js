// Se realiza fetch al backend de api peliculas

// Base de la api peliculas
const BASE_URL = 'http://localhost/cinema-online-ticket/api';

// Obtener todas las películas
export async function getPeliculas() {
    const res = await fetch(`${BASE_URL}/peliculas`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cargar películas');
    }

    return data.datos;
}

// Obtener película por ID
export async function obtenerPeliculaPorId(id) {
    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener película');
    }

    return data.datos;
}

// Obtener películas completas
export async function obtenerPeliculasCompletas() {
    const res = await fetch(`${BASE_URL}/completas`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener películas completas');
    }

    return data.datos;
}

// Crear película
export async function crearPelicula(datos) {
    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al crear película');
    }

    return data.datos;
}

// Actualizar película
export async function actualizarPelicula(id, datos) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al actualizar película');
    }

    return data.datos;
}

// Activar película
export async function activarPelicula(id) {
    const res = await fetch(`${BASE_URL}/${id}/activar`, {
        method: 'PATCH'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al activar película');
    }

    return data.datos;
}

// Desactivar película
export async function desactivarPelicula(id) {
    const res = await fetch(`${BASE_URL}/${id}/desactivar`, {
        method: 'PATCH'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al desactivar película');
    }

    return data.datos;
}
