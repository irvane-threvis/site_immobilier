<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Favorite.php';
requireRole('client');

$favoriteModel = new Favorite($pdo);
$favorites = $favoriteModel->getFavorites($_SESSION['user_id']);

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Mes favoris</h1>

    <?php if (empty($favorites)): ?>
        <p class="empty-msg">Aucun favori pour le moment. <a href="<?= url('views/property/list.php') ?>">Parcourir les annonces</a></p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($favorites as $property): ?>
                <div class="property-card">
                    <img src="https://picsum.photos/seed/<?= $property['id'] ?>/400/250" alt="">
                    <div class="property-content">
                        <h3><?= htmlspecialchars($property['titre']) ?></h3>
                        <p>📍 <?= htmlspecialchars($property['ville']) ?></p>
                        <p>💰 <?= number_format($property['prix'], 0, ',', ' ') ?> FCFA</p>
                        <a href="<?= url('views/property/detail.php?id=' . $property['id']) ?>">Voir détails</a>
                        <a class="danger-btn" href="<?= url('controllers/ClientController.php?action=removeFavorite&property=' . $property['id']) ?>">Retirer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
