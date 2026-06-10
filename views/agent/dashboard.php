<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('agent');

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="dashboard page-content">
    <h1>Espace Agent</h1>
    <div class="dashboard-grid">
        <a class="dashboard-card" href="<?= url('views/agent/validations.php') ?>">
            <i class="fa-solid fa-clipboard-check"></i> Valider
        </a>
        <a class="dashboard-card" href="<?= url('views/agent/visits.php') ?>">
            <i class="fa-solid fa-calendar-check"></i> Visites
        </a>
        <a class="dashboard-card" href="<?= url('views/agent/clients.php') ?>">
            <i class="fa-solid fa-users"></i> Clients
        </a>
    </div>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
