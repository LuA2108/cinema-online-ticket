<!-- views/partials/head.php -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>🎥 <?= $title ?? 'Cinema Online Ticket' ?></title>
    <meta name="description" content="<?= $descripcion ?? 'Cinema Online Ticket' ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BootStrap - Font Awesome - Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b527a55e68.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="public/assets/css/main.css">

    <!-- CSS específico por página -->
    <?php if (isset($extraCSS)): ?>
        <link rel="stylesheet" href="public/assets/css/<?= $extraCSS ?>.css">
    <?php endif; ?>

</head>