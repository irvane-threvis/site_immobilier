<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
requireRole('bailleur');

$propertyModel = new Property($pdo);
$properties = $propertyModel->getByOwner($_SESSION['user_id']);

$statusLabels = array(
    'pending' => 'Attente',
    'published' => 'Publiée',
    'refused' => 'Refusée',
    'retired' => 'Retirée'
);

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="dashboard page-content">
    <div class="page-header">
        <h1>Espace Bailleur</h1>
        <a href="<?= url('views/property/create.php') ?>" class="success-btn">+ Annonce</a>
    </div>

    <?php if (empty($properties)): ?>
        <div class="empty-box">
            <p>Aucune annonce.</p>
            <a href="<?= url('views/property/create.php') ?>" class="btn-primary" style="display:inline-block;width:auto;padding:12px 24px;">Ajouter un bien</a>
        </div>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Titre</th>
                <th>Ville</th>
                <th>Prix</th>
                <th>Statut</th>
                <th></th>
            </tr>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= htmlspecialchars($property['titre']) ?></td>
                    <td><?= htmlspecialchars($property['ville']) ?></td>
                    <td><?= number_format($property['prix'], 0, ',', ' ') ?> F</td>
                    <td><span class="badge badge-<?= $property['status'] ?>"><?= isset($statusLabels[$property['status']]) ? $statusLabels[$property['status']] : $property['status'] ?></span></td>
                    <td>
                        <a class="success-btn" href="<?= url('views/property/edit.php?id=' . $property['id']) ?>">Edit</a>
                        <a class="danger-btn" href="<?= url('controllers/PropertyController.php?action=delete&id=' . $property['id']) ?>" onclick="return confirm('Supprimer ?')">×</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
