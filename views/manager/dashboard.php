<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('manager');

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="dashboard page-content">
    <h1>Administration</h1>
    <div class="dashboard-grid">
        <a class="dashboard-card" href="<?= url('views/manager/users.php') ?>">
            <i class="fa-solid fa-users"></i> Users
        </a>
        <a class="dashboard-card" href="<?= url('views/manager/assignments.php') ?>">
            <i class="fa-solid fa-user-group"></i> Affectations
        </a>
        <a class="dashboard-card" href="<?= url('views/manager/statistics.php') ?>">
            <i class="fa-solid fa-chart-bar"></i> Stats
        </a>
        <a class="dashboard-card" href="<?= url('views/manager/properties.php') ?>">
            <i class="fa-solid fa-house"></i> Annonces
        </a>
    </div>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
