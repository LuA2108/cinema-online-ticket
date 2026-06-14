class CarouselPrincipal {

    renderizar(peliculas) {
        
        const indicadores = document.getElementById("carousel-indicators");
        const inner = document.getElementById("carousel-inner");

        indicadores.innerHTML = "";
        inner.innerHTML = "";

        peliculas.forEach((pelicula, index) => {

            const active = index === 0 ? "active" : "";

            indicadores.insertAdjacentHTML(
                "beforeend",
                `
                <button
                    type="button"
                    data-bs-target="#carousel-peliculas"
                    data-bs-slide-to="${index}"
                    class="${active}"
                    ${index === 0 ? 'aria-current="true"' : ""}
                ></button>
                `
            );

            inner.insertAdjacentHTML(
                "beforeend",
                `
                <div class="carousel-item ${active}">
                    <img
                        src="${pelicula.banner}"
                        class="d-block w-100 img-carousel"
                        alt="${pelicula.titulo}"
                    >

                    <section class="capa">
                        <h2 class="titulo-pelicula">
                            ${pelicula.titulo}
                        </h2>

                        <a href="#"
                            class="btn-reservar">
                            ¡Compra ya tus entradas!
                        </a>
                    </section>
                </div>
                `
            );
        });
    }
}

export default CarouselPrincipal;