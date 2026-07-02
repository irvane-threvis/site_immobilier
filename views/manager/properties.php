<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
requireLogin();
if (!in_array(currentUserRole(), ['manager', 'agent'])) {
    redirectByRole(currentUserRole());
}

$propertyModel = new Property($pdo);
$properties = $propertyModel->getAll();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Gestion des annonces</h1>

    <?php if (empty($properties)): ?>
        <p class="empty-msg">Aucune annonce publiée.</p>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Titre</th>
                <th>Ville</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= htmlspecialchars($property['titre']) ?></td>
                    <td><?= htmlspecialchars($property['ville']) ?></td>
                    <td><?= number_format($property['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td>
                        <span class="badge badge-<?= $property['status'] ?>">
                            <?= ucfirst($property['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a class="success-btn" href="<?= url('views/property/detail.php?id=' . $property['id']) ?>">Visualiser</a>
                        
                        <?php if ($property['status'] === 'pending'): ?>
                            <a class="success-btn" style="background-color: #2d6a4f;" href="<?= url('controllers/PropertyController.php?action=validate&id=' . $property['id']) ?>" onclick="return confirm('Publier cette annonce ?')">Valider</a>
                            <a class="danger-btn" href="<?= url('controllers/PropertyController.php?action=refuse&id=' . $property['id']) ?>" onclick="return confirm('Refuser cette annonce ?')">Refuser</a>
                        <?php elseif ($property['status'] === 'published' && currentUserRole() === 'manager'): ?>
                            <a class="danger-btn" href="<?= url('controllers/PropertyController.php?action=retire&id=' . $property['id']) ?>" onclick="return confirm('Retirer cette annonce du site ?')">Retirer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
