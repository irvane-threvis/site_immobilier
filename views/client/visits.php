<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/VisitRequest.php';
requireRole('client');

$model = new VisitRequest($pdo);
$visits = $model->getClientVisits($_SESSION['user_id']);

$statusLabels = [
    'pending' => 'En attente',
    'approved' => 'Approuvée',
    'rejected' => 'Refusée'
];

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Mes demandes de visite</h1>

    <?php if (empty($visits)): ?>
        <p class="empty-msg">Aucune demande de visite. <a href="<?= url('views/property/list.php') ?>">Voir les annonces</a></p>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Bien</th>
                <th>Ville</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
            <?php foreach ($visits as $visit): ?>
                <tr>
                    <td><?= htmlspecialchars($visit['titre']) ?></td>
                    <td><?= htmlspecialchars($visit['ville']) ?></td>
                    <td><span class="badge badge-<?= $visit['status'] ?>"><?= $statusLabels[$visit['status']] ?? $visit['status'] ?></span></td>
                    <td><?= htmlspecialchars($visit['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
