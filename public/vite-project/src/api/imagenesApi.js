// Se realiza fetch al backend de api peliculas

// Base de la api peliculas
const BASE_URL = 'http://localhost/cinema-online-ticket/api';

// Obtener todas las imagenes
export async function obtenerImagenes() {
    const res = await fetch(`${BASE_URL}/imagenes`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cargar las imagenes');
    }

    return data.datos;
}

// Obtener imagen por ID
export async function obtenerImagenPorId(id) {
    const res = await fetch(`${BASE_URL}/pelicula/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener la imagen');
    }

    return data.datos;
}

// Obtener imagen por ID de película
export async function obtenerImagenPorPelicula(id_pelicula) {
    const res = await fetch(`${BASE_URL}/pelicula/${id_pelicula}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || `Error al obtener la imagen de la película ${id_pelicula}`);
    }

    return data.datos;
}

// Crear imagen
export async function agregarImagen(datos) {
    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al crear la película');
    }

    return data.datos;
}

// Eliminar una imagen
export async function eliminarImagen(id) {
    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || `Error al eliminar la película ${id}`);
    }

    return data.datos;
}
