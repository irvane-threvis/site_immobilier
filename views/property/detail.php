<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
require_once ROOT_PATH . 'models/Favorite.php';

$propertyModel = new Property($pdo);
$property = $propertyModel->getById((int) (isset($_GET['id']) ? $_GET['id'] : 0));

if (!$property || $property['status'] !== 'published') {
    flash('error', 'Propriété introuvable.');
    redirect('biens.php');
}

$images = $propertyModel->getImages($property['id']);
$isFavorite = false;

if (isLoggedIn() && currentUserRole() === 'client') {
    $favoriteModel = new Favorite($pdo);
    $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $property['id']);
}

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="details-container page-content">
    <h1><?= htmlspecialchars($property['titre']) ?></h1>

    <div class="gallery" id="property-gallery">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $index => $img): ?>
                <img src="<?= url('uploads/properties/' . $img['image_path']) ?>"
                     alt="Photo <?= $index + 1 ?>"
                     class="gallery-thumb"
                     data-index="<?= $index ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <img src="https://picsum.photos/seed/<?= $property['id'] ?>/800/500" alt="Image" class="gallery-thumb">
        <?php endif; ?>
    </div>

    <div id="lightbox" class="lightbox hidden">
        <span class="lightbox-close">&times;</span>
        <img id="lightbox-img" src="" alt="Zoom">
    </div>

    <div class="property-meta">
        <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($property['ville']) ?> — <?= htmlspecialchars($property['adresse']) ?></p>
        <p><strong><?= number_format($property['prix'], 0, ',', ' ') ?> FCFA</strong><?= $property['option_type'] === 'location' ? '/mois' : '' ?></p>
        <p><?= htmlspecialchars($property['superficie']) ?> m² · <?= ucfirst($property['type']) ?> · <?= $property['option_type'] === 'vente' ? 'À vendre' : 'À louer' ?></p>
    </div>

    <div class="description">
        <h3>Description</h3>
        <?= nl2br(htmlspecialchars($property['description'])) ?>
    </div>

    <h3>Localisation</h3>
    <iframe width="100%" height="400" style="border:0" loading="lazy" allowfullscreen
        src="https://www.google.com/maps?q=<?= urlencode($property['ville'] . ' ' . $property['adresse']) ?>&output=embed">
    </iframe>

    <div class="action-zone">
        <?php if (isLoggedIn() && currentUserRole() === 'client'): ?>
            <div class="action-buttons">
                <?php if ($isFavorite): ?>
                    <a class="favorite-btn" href="<?= url('controllers/ClientController.php?action=removeFavorite&property=' . $property['id']) ?>"><i class="fa-solid fa-heart"></i> Retirer des favoris</a>
                <?php else: ?>
                    <a class="favorite-btn" href="<?= url('controllers/ClientController.php?action=favorite&property=' . $property['id']) ?>"><i class="fa-regular fa-heart"></i> Ajouter aux favoris</a>
                <?php endif; ?>
                <a class="visit-btn" href="<?= url('controllers/ClientController.php?action=visit&property=' . $property['id']) ?>"><i class="fa-solid fa-calendar"></i> Demander une visite</a>
            </div>
        <?php elseif (!isLoggedIn()): ?>
            <div class="visitor-notice">
                <p><i class="fa-solid fa-eye"></i> Vous consultez ce bien en tant que <strong>visiteur</strong>.</p>
                <p>Les favoris et les demandes de visite sont réservés aux <strong>clients inscrits</strong>.</p>
                <div class="visitor-actions">
                    <a href="<?= url('inscription.php?role=client') ?>" class="btn-primary visitor-btn">S'inscrire comme Client</a>
                    <a href="<?= url('connexion.php') ?>" class="btn-login visitor-btn">Déjà inscrit ? Connexion</a>
                </div>
            </div>
        <?php else: ?>
            <div class="visitor-notice visitor-notice-muted">
                <p>Ces actions sont réservées aux comptes <strong>Client</strong>.</p>
                <a href="<?= url('biens.php') ?>">← Retour aux biens</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
