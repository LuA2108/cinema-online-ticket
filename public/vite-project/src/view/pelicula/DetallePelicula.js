class DetallePelicula {

    renderizarPelicula(pelicula) {

        document.querySelector('[data-pelicula="titulo"]').textContent = pelicula.titulo;
        document.querySelector('[data-pelicula="anio"]').textContent = pelicula.anio;
        document.querySelector('[data-pelicula="duracion"]').textContent = `${pelicula.duracion} min`;
        document.querySelector('[data-pelicula="descripcion"]').textContent = pelicula.descripcion;
        document.querySelector('[data-pelicula="director"]').textContent = pelicula.director;
        document.querySelector('[data-pelicula="disponible"]').textContent = pelicula.disponible == 1 ? "Disponible" : "No disponible";
        document.querySelector('[data-pelicula="destacado"]').textContent = pelicula.destacado == 1
            ? "Destacada"
            : "";

        // Trailer (siempre el mismo, porque no se agrego un campo de trailer a la bd)
        document.querySelector('[data-pelicula="trailer"]').href = "https://www.youtube.com/watch?v=ePbKGoIGAXY";

        if (pelicula.disponible == 1) {
            document.getElementById("enlace-reserva").innerHTML = "Reserva";
            document.getElementById("enlace-reserva").href = `./reserva/seleccionar_funcion.html?id=${pelicula.id}`;
        } else {
            document.getElementById("enlace-reserva").classList.add("d-none");
        }
    }

    renderizarImagenes(imagenes) {

        const poster = imagenes.find(img => img.tipo === "poster");
        const banner = imagenes.find(img => img.tipo === "banner");

        const posterEl = document.querySelector('[data-pelicula="poster"]');
        const bannerEl = document.querySelector('[data-pelicula="banner"]');

        const URL_BASE = "http://localhost/cinema-online-ticket";

        if (poster) {
            posterEl.src = URL_BASE + poster.url;
            posterEl.alt = "Poster";
        }

        if (banner) {
            bannerEl.src = URL_BASE + banner.url;
            bannerEl.alt = "Banner";
        }
    }

    renderizarGeneros(generos) {

        const contenedor = document.querySelector('[data-pelicula="generos"]');
        contenedor.innerHTML = "";

        generos.forEach(g => {

            const span = document.createElement("span");
            span.classList.add("genero");

            span.textContent = (typeof g === "string") ? g : g.nombre;

            contenedor.appendChild(span);
        });
    }
}

export default DetallePelicula;