    <!DOCTYPE html>
    <html>
    <!-- HEAD -->
    <?php require __DIR__ . "/../partials/head.php"; ?>

    <body class="admin">
        <!-- CABECERA -->
        <?php require __DIR__ . "/../partials/sidebar.php"; ?>
        
        <main class="main-admin">
            <?= $content; ?> <!-- Aquí se inserta la vista que prepara el controlador -->
        </main>

        <script src="" async defer></script>
    </body>

    </html>