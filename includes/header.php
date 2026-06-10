<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php $flash = getFlash(); ?>
<?php if ($flash): ?>
<div class="toast toast-<?= htmlspecialchars($flash['type']) ?>" id="flash-toast">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>
