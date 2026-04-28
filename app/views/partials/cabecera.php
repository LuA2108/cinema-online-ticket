<!-- ======= Cabecera : Logo / Buscador / Menu  ======= -->
<header>

    <div class="contenedor-logo-sesion">
        <!-- LOGO -->
        <div class="logo">
            <a href="index.php"><img src="public/assets/imgs/iconos/logo_cine.png" alt="Icono logo cine" width="170px" height="100px"></a>
        </div>

        <!-- SUBMENU para SESION-->
        <nav class="menu-sesion">
            <ul>
                <li class="dropdown">
                    <a href="#"> <i class="fa-solid fa-user" style="color: rgb(239, 244, 255);"></i></a>
                    <ul class="submenu">
                        <li><a href="index.php?page=auth/login">Iniciar sesión</a></li>
                        <li><a href="index.php?page=auth/registro">Registrarse</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Menu y buscador -->
    <div class="container-menu-buscador">
        <!-- MENU -->
        <nav class="menu">
            <ul class="">
                <li class="menu-item">
                    <a class="item-peliculas" href="#"> <i class="fa-solid fa-clapperboard"></i> Películas</a>
                </li>
                <li class="menu-item">
                    <a class="item-promociones" href="#"><i class="fa-solid fa-euro-sign"></i> Promociones</a>
                </li>
                <li class="menu-item">
                    <a class="item-servicios" href="#"> <img class="icono" src="public/assets/imgs/iconos/palomitas-de-maiz.png" alt="Icono de palomitas"> Servicios</a>
                </li>
                <li class="menu-item">
                    <a class="item-info" href="#"> <i class="fa-solid fa-info"></i>Sobre Nosotros</a>
                </li>
                <li class="menu-item">
                    <a class="item-reserva" href="#"> <i class="fa-solid fa-ticket"></i> Reservar película</a>
                </li>
                <li class="menu-item buscador">
                    <img src="public/assets/imgs/iconos/buscar.png" alt="Icono buscador" width="30" height="30">
                    <form action="#" method="get">
                        <input type="search" name="buscador" placeholder="Buscar película...">
                    </form>
                </li>
            </ul>
        </nav>
</header>
<hr>