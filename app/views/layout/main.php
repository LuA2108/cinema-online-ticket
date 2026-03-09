<!DOCTYPE html>
<html>
<!-- HEAD -->
<?php require __DIR__ . "/../partials/head.php"; ?>

<body>
    <!-- CABECERA -->
    <?php require __DIR__ . "/../partials/cabecera.php"; ?>
    
    <main class="container-main">
        <?= $content; ?> <!-- Aquí se inserta la vista que prepara el controlador -->
    </main>

    <?php require __DIR__ . "/../partials/footer.php"; ?>
    <script src="" async defer></script>
</body>

</html>