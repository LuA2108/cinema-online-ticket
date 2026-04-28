<?php 

?>
<section class="container_login">
    <h1>Bienvenido</h1>

    <div class="auth_form">
        <h2 class="login_titulo">Iniciar sesión</h2>
        
        <!-- Formulario - inicio sesión -->
        <form action="index.php?page=auth/login.php" method="post">
            
            <!-- Email  -->
            <label for="email">Email</label>
            <input type="text" id="email" name="email"  placeholder="Correo">
            
            <!-- Contraseña -->
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña">
            
            
            <!-- Confirmacion y enlace -->
            <button class="button" type="submit">Enviar</button>
            <p>¿No tienes cuenta? <a href="index.php?page=auth/registro">Registrate</a></p>
        </form>

    </div>
</section>