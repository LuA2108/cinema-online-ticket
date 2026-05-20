<!-- HEADER -->
<div class="header-admin"></div>

<!-- CHECKBOX -->
<input type="checkbox" id="openSidebarMenu" checked>

<!-- BOTÓN HAMBURGUESA -->
<label for="openSidebarMenu" class="sidebarIconToggle">
    <div class="spinner diagonal part-1"></div>
    <div class="spinner horizontal"></div>
    <div class="spinner diagonal part-2"></div>
</label>

<!-- SIDEBAR -->
<aside class="sidebar-menu">
    <!-- contenido del sidebar: enlaces, titulo e imagen -->
    <div class="container-admin">
        <img src="public/imgs/admin-con-ruedas-dentadas.png" alt="Imagen de Administrador">
        <h2>Administrador</h2>
    </div>
    <hr>
    <ul class="sidebar-menu-inner">
        <li>Panel de control</li>
        <li><a href="?page=admin/index"><i class="fa-solid fa-house" style="color: rgb(239, 244, 255);"></i>Inicio</a></li>
        <li><a href="?page=admin/usuarios/index"><i class="fa-solid fa-user" style="color: rgb(239, 244, 255);"></i>Usuarios</a></li>
        <li><a href="?page=admin/peliculas/index"><i class="fa-solid fa-video" style="color: rgb(239, 244, 255);"></i>Películas</a></li>
        <li><a href="?page=admin/generos/index"><i class="fa-solid fa-masks-theater" style="color: rgb(239, 244, 255);"></i>Géneros películas</a></li>
        <li><a href="?page=admin/funciones/index"><i class="fa-solid fa-tv" style="color: rgb(239, 244, 255);"></i>Funciones</a></li>
        <li><a href="?page=admin/reservas/index"><i class="fa-solid fa-calendar" style="color: rgb(239, 244, 255);"></i>Reservas</a></li>
        <li><a href="?page=admin/salas/index"><i class="fa-solid fa-clapperboard" style="color: rgb(239, 244, 255);"></i>Salas</a></li>
        <li><a href="?page=admin/productos/index"><i class="fa-solid fa-candy-cane" style="color: rgb(239, 244, 255);"></i>Productos</a></li>
    </ul>

    <form action="index.php?page=auth/logout" method="POST">
        <button type="submit">Cerrar sesión</button>
    </form>
</aside>


