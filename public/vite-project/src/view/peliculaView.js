class PeliculaView {

    constructor() {
        //por ahora vacio
    }

    renderizarTablaPeliculas(peliculas) {

        const tbody = document.getElementById('tablaPeliculasBody');

        tbody.innerHTML = '';

        peliculas.forEach(pelicula => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${pelicula.id}</td>
                <td>${pelicula.titulo}</td>
                <td>${pelicula.descripcion}</td>
                <td>${pelicula.director}</td>
                <td>${pelicula.anio}</td>
                <td>${pelicula.duracion} minutos</td>
                <td>${pelicula.precio} €</td>
                <td>
                    ${pelicula.disponible ? 'Sí' : 'No'}
                </td>
            `;

            tbody.appendChild(tr);
        });
    }
}

export default PeliculaView;