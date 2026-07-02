<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/VisitRequest.php';
requireRole('agent');

$model = new VisitRequest($pdo);
$visits = $model->getPendingVisits();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Demandes de visite</h1>

    <?php if (empty($visits)): ?>
        <p class="empty-msg">Aucune demande de visite en attente.</p>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Bien</th>
                <th>Action</th>
            </tr>
            <?php foreach ($visits as $visit): ?>
                <tr>
                    <td><?= htmlspecialchars($visit['nom'] . ' ' . $visit['prenom']) ?></td>
                    <td><?= htmlspecialchars($visit['email']) ?></td>
                    <td><?= htmlspecialchars($visit['telephone']) ?></td>
                    <td><?= htmlspecialchars($visit['titre']) ?></td>
                    <td>
                        <a class="success-btn" href="<?= url('controllers/AgentController.php?action=approveVisit&id=' . $visit['id']) ?>">Valider</a>
                        <a class="danger-btn" href="<?= url('controllers/AgentController.php?action=rejectVisit&id=' . $visit['id']) ?>">Refuser</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
