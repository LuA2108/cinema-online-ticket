class CarouselTarjetas {

    renderizar(peliculas) {

        const container = document.getElementById("peliculas-container");

        container.innerHTML = "";

        peliculas.forEach(pelicula => {
            const poster = pelicula.poster;

            container.insertAdjacentHTML(
                "beforeend",
                `
                <div class="pelicula-card">
                    <img 
                        src="${poster}" 
                        alt="${pelicula.titulo}" 
                        class="pelicula-img"
                    >

                    <h3 class="pelicula-titulo">
                        ${pelicula.titulo}
                    </h3>

                    <a href="/detalle.html?id=${pelicula.id}" class="pelicula-boton">
                        Ver más
                    </a>
                </div>
                `
            );
        });
    }
}

export default CarouselTarjetas;