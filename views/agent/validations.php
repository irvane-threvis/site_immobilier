<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
requireRole('agent');

$model = new Property($pdo);
$properties = $model->getPendingProperties();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Validation des annonces</h1>

    <?php if (empty($properties)): ?>
        <p class="empty-msg">Aucune annonce en attente de validation.</p>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Titre</th>
                <th>Bailleur</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Ville</th>
                <th>Action</th>
            </tr>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= htmlspecialchars($property['titre']) ?></td>
                    <td><?= htmlspecialchars($property['nom'] . ' ' . $property['prenom']) ?></td>
                    <td><?= htmlspecialchars($property['email']) ?></td>
                    <td><?= htmlspecialchars($property['telephone']) ?></td>
                    <td><?= htmlspecialchars($property['ville']) ?></td>
                    <td>
                        <a class="success-btn" style="background-color: var(--primary);" href="<?= url('views/property/detail.php?id=' . $property['id']) ?>">Visualiser</a>
                        <a class="success-btn" href="<?= url('controllers/PropertyController.php?action=validate&id=' . $property['id']) ?>">Valider</a>
                        <a class="danger-btn" href="<?= url('controllers/PropertyController.php?action=refuse&id=' . $property['id']) ?>">Refuser</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
