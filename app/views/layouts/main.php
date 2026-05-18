<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <meta name="description" content="NovaShop, tienda online de figuras, gaming, anime y fantasia con una experiencia de compra moderna y clara.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <?php require VIEW_PATH . '/partials/navbar.php'; ?>

    <main class="flex-grow-1 py-4">
        <div class="container">
            <?php require VIEW_PATH . '/partials/flash.php'; ?>
            <?= $content ?>
        </div>
    </main>

    <?php require VIEW_PATH . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset_url('js/app.js')) ?>"></script>
</body>
</html>
