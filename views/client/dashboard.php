<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('client');

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="dashboard page-content">
    <h1>Espace Client</h1>
    <div class="dashboard-grid">
        <a class="dashboard-card" href="<?= url('biens.php') ?>">
            <i class="fa-solid fa-search"></i> Biens
        </a>
        <a class="dashboard-card" href="<?= url('views/client/favorites.php') ?>">
            <i class="fa-solid fa-heart"></i> Favoris
        </a>
        <a class="dashboard-card" href="<?= url('views/client/visits.php') ?>">
            <i class="fa-solid fa-calendar"></i> Visites
        </a>
    </div>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
