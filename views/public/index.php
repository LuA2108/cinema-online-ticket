<h1> ¡Vive la magia del <span>Cine</span>!</h1>

<section id="carrousel-principal">

    <div class="container">
        <div id="carousel-peliculas" class="carousel slide" data-bs-ride="carousel">

            <!-- Indicadores -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel-peliculas" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carousel-peliculas" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carousel-peliculas" data-bs-slide-to="2"></button>
            </div>

            <!-- CarruselImg -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/cinema-online-ticket/public/assets/imgs/pelicula_imagen/banner/inception_banner.jpg" class="d-block w-100 img-carousel" alt="Portada Inception">
                    <!-- Contenedor para el título y el enlace -->
                    <section class="capa">
                        <h2 class="titulo-pelicula">Inception</h2>
                        <a href="#" class="btn-reservar">¡Compra ya tus entradas!</a>
                    </section>
                </div>
                <div class="carousel-item">
                    <img src="/cinema-online-ticket/public/assets/imgs/pelicula_imagen/banner/avatar_banner.jpg" class="d-block w-100" alt="Portada Avatar">
                    <section class="capa">
                        <h2 class="titulo-pelicula">Avatar</h2>
                        <a href="#" class="btn-reservar">¡Compra ya tus entradas!</a>
                    </section>
                </div>
                <div class="carousel-item">
                    <img src="/cinema-online-ticket/public/assets/imgs/pelicula_imagen/banner/jocker_banner.png" class="d-block w-100 h-50" alt="Portada Jocker">
                    <section class="capa">
                        <h2 class="titulo-pelicula">Jocker</h2>
                        <a href="#" class="btn-reservar">¡Compra ya tus entradas!</a>
                    </section>
                </div>
            </div>

            <!-- Controladores - Anterior & Siguiente -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-peliculas" data-bs-slide="prev">
                <span class="carousel-control-prev-icon hexagono"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-peliculas" data-bs-slide="next">
                <span class="carousel-control-next-icon hexagono"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

    </div>
</section>

<section id="proximas-peliculas-container">
    <h2 class="titulo">Proximos estrenos</h2>
    
    <div class="peliculas-container">
        <!-- Tarjeta 1 -->
        <div class="pelicula-card">
            <img src="#" alt="Título Película 1" class="pelicula-img">
            <h3 class="pelicula-titulo">Película 1</h3>
            <a href="#" class="pelicula-boton">Ver más</a>
        </div>

        <!-- Tarjeta 2 -->
        <div class="pelicula-card">
            <img src="#" alt="Título Película 2" class="pelicula-img">
            <h3 class="pelicula-titulo">Película 2</h3>
            <a href="#" class="pelicula-boton">Ver más</a>
        </div>

        <!-- Tarjeta 3 -->
        <div class="pelicula-card">
            <img src="#" alt="Título Película 3" class="pelicula-img">
            <h3 class="pelicula-titulo">Película 3</h3>
            <a href="#" class="pelicula-boton">Ver más</a>
        </div>

        <!-- Tarjeta 4 -->
        <div class="pelicula-card">
            <img src="#" alt="Título Película 4" class="pelicula-img">
            <h3 class="pelicula-titulo">Película 4</h3>
            <a href="#" class="pelicula-boton">Ver más</a>
        </div>

        <!-- Tarjeta 5 -->
        <div class="pelicula-card">
            <img src="#" alt="Título Película 5" class="pelicula-img">
            <h3 class="pelicula-titulo">Película 5</h3>
            <a href="#" class="pelicula-boton">Ver más</a>
        </div>
    </div>

</section>

<section id="registro-info">
    <h2>¡Mantente al Día con Nuestro Cine!</h2>
    <p>Regístrate con tu correo electrónico y recibe información sobre estrenos, promociones y eventos especiales.</p>

    <form action="suscripcion.php" method="post" class="newsletter-form d-flex justify-content-center">
        <input type="email" name="email" placeholder="Tu correo electrónico" required class="form-control me-2" />
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-envelope"></i>Suscribirse
        </button>
    </form>
</section>