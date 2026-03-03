<!-- views/partials/head.php -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>🎥 <?= $title ?? 'Cinema Online Ticket' ?></title>
    <meta name="description" content="<?= $descripcion ?? 'Cinema Online Ticket'?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BootStrap y Font Awesome-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- CSS global -->
    <link rel="stylesheet" href="public/assets/css/main.css">

    <!-- CSS específico por página -->
    <?php if (isset($extraCSS)): ?>
        <link rel="stylesheet" href="public/assets/css/<?= $extraCSS ?>.css">
    <?php endif; ?>

</head>