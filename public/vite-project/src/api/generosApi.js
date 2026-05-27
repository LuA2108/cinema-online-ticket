// Se realiza fetch al backend de api peliculas

// Base de la api Generos (Pelicula)
const BASE_URL = 'http://localhost/cinema-online-ticket/api/generos';

// Obtener todas las imagenes
export async function obtenerGeneros() {
    const res = await fetch(`${BASE_URL}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cargar los géneros.');
    }

    return data.datos;
}

// Obtener Generos con datos relacionados
export async function obtenerGenerosRelacion() {
    const res = await fetch(`${BASE_URL}/completas`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener los generos y su relacion');
    }

    return data.datos;
}


// Obtener genero por ID
export async function obtenerGenero(id) {
    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || `Error al obtener la genero con ${id}`);
    }

    return data.datos;
}


// Crear Genero
export async function agregarGenero(datos) {
    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || `Error al crear genero.`);
    }

    return data.datos;
}

// Eliminar un genero por ID
export async function eliminarGenero(id) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || `Error al eliminar el género ${id}`);
    }

    return data.datos;
}
