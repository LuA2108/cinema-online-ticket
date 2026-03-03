<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <?php require __DIR__ . "/../partials/head.php"; ?>

    <!-- CABECERA -->
    <?php require __DIR__ . "/../partials/cabecera.php"; ?>

    <body>
        <main class="container">
            <?= $content; ?> <!-- Aquí se inserta la vista que prepara el controlador -->
        </main>
    
        <?php require __DIR__ . "/../partials/footer.php"; ?>
        <script src="" async defer></script>
    </body>

</html>