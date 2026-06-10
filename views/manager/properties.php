<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('manager');

$properties = $pdo->query("SELECT * FROM properties WHERE status = 'published' ORDER BY id DESC")->fetchAll();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Annonces publiées</h1>

    <?php if (empty($properties)): ?>
        <p class="empty-msg">Aucune annonce publiée.</p>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Titre</th>
                <th>Ville</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>
            <?php foreach ($properties as $property): ?>
                <tr>
                    <td><?= htmlspecialchars($property['titre']) ?></td>
                    <td><?= htmlspecialchars($property['ville']) ?></td>
                    <td><?= number_format($property['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td>
                        <a class="danger-btn" href="<?= url('controllers/PropertyController.php?action=retire&id=' . $property['id']) ?>" onclick="return confirm('Retirer cette annonce ?')">Retirer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
