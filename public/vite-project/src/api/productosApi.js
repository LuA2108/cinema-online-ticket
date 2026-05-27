// Base API productos
const BASE_URL = 'http://localhost/cinema-online-ticket/api/productos';

// Obtener todos los productos
export async function obtenerProductos() {

    const res = await fetch(BASE_URL);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al cargar productos');
    }

    return data.datos;
}

// Obtener producto por ID
export async function obtenerProductoPorId(id) {

    const res = await fetch(`${BASE_URL}/${id}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener producto');
    }

    return data.datos;
}

// Obtener productos por tipo
export async function obtenerProductosPorTipo(tipoId) {

    const res = await fetch(`${BASE_URL}/tipo/${tipoId}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al obtener productos por tipo');
    }

    return data.datos;
}

// Buscar productos por nombre
export async function buscarProductos(nombre) {

    const res = await fetch(`${BASE_URL}/buscar/${encodeURIComponent(nombre)}`);
    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al buscar productos');
    }

    return data.datos;
}

// Crear producto
export async function agregarProducto(datos) {

    const res = await fetch(BASE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al crear producto');
    }

    return data.datos;
}

// Actualizar producto
export async function actualizarProducto(id, datos) {

    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al actualizar producto');
    }

    return data.datos;
}

// Eliminar producto
export async function eliminarProducto(id) {

    const res = await fetch(`${BASE_URL}/${id}`, {
        method: 'DELETE'
    });

    const data = await res.json();

    if (!data.success) {
        throw new Error(data.error || 'Error al eliminar producto');
    }

    return data.datos;
}