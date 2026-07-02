<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('manager');

$totalProperties = $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
$pendingProperties = $pdo->query("SELECT COUNT(*) FROM properties WHERE status = 'pending'")->fetchColumn();
$publishedProperties = $pdo->query("SELECT COUNT(*) FROM properties WHERE status = 'published'")->fetchColumn();
$totalVisits = $pdo->query("SELECT COUNT(*) FROM visit_requests")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Statistiques</h1>

    <div class="stats-container">
        <div class="stat-card">
            <h2><?= $totalProperties ?></h2>
            <p>Propriétés</p>
        </div>
        <div class="stat-card">
            <h2><?= $pendingProperties ?></h2>
            <p>En attente</p>
        </div>
        <div class="stat-card">
            <h2><?= $totalVisits ?></h2>
            <p>Demandes de visite</p>
        </div>
        <div class="stat-card">
            <h2><?= $totalUsers ?></h2>
            <p>Utilisateurs</p>
        </div>
    </div>

    <canvas id="statsChart"></canvas>
</div>

<script src="<?= url('assets/js/chart.min.js') ?>"></script>
<script>
new Chart(document.getElementById('statsChart'), {
    type: 'bar',
    data: {
        labels: ['Total', 'Publiées', 'En attente', 'Visites', 'Utilisateurs'],
        datasets: [{
            label: 'Statistiques ImmoFaso',
            data: [<?= $totalProperties ?>, <?= $publishedProperties ?>, <?= $pendingProperties ?>, <?= $totalVisits ?>, <?= $totalUsers ?>],
            backgroundColor: ['#1e4d7b', '#2d6a4f', '#8b7355', '#8b2e3a', '#5a6578']
        }]
    },
    options: { responsive: true }
});
</script>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
