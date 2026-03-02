<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <?= require __DIR__ . "../partials/head.php"; ?>

    <!-- CABECERA -->
    <?= require __DIR__ . "../partials/cabecera.php"; ?>

    <body>
        <main class="container">
            <?= $content ?> <!-- Aquí se inserta la vista que prepara el controlador -->
        </main>
    
        <script src="" async defer></script>
    </body>
</html>