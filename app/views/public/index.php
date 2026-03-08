<h1> ¡Vive la magia de <span>CineToon</span>!</h1>

<section id="carrousel-principal">

    <div class="container mt-5">
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
                    <img src="public/assets/imgs/films/banner/inception_hor.jpg" class="d-block w-100 img-carousel" alt="Portada Inception">
                    <!-- Contenedor para el título y el enlace -->
                    <section class="capa">
                        <h2 class="titulo-pelicula">Inception</h2>
                        <a href="#" class="btn-reservar">¡Compra ya tus entradas!</a>
                    </section>
                </div>
                <div class="carousel-item">
                    <img src="public/assets/imgs/films/banner/avatarBanner.jpg" class="d-block w-100" alt="Portada Avatar">
                    <section class="capa">
                        <h2 class="titulo-pelicula">Avatar</h2>
                        <a href="#" class="btn-reservar">¡Compra ya tus entradas!</a>
                    </section>
                </div>
                <div class="carousel-item">
                    <img src="public/assets/imgs/films/banner/jocker_hor.png" class="d-block w-100 h-50" alt="Portada Jocker">
                    <section class="capa">
                        <h2 class="titulo-pelicula">Jocker</h2>
                        <a href="#" class="btn-reservar">¡Compra ya tus entradas!</a>
                    </section>
                </div>
            </div>

            <!-- Controladores - Anterior & Siguiente -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-peliculas" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-peliculas" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

    </div>
</section>

<section id="proximas-peliculas">
    <h2>Proximos estrenos</h2>
    <div class="carousel-item">
        <img src="#" alt="Pelicula estreno A">
        <h3>Nombre pelicula</h3>
        <a href="#" class="btn btn-primary">Comprar Entrada</a>
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