<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(__DIR__, 2)) . DIRECTORY_SEPARATOR);
}
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
require_once ROOT_PATH . 'models/Favorite.php';

$propertyModel = new Property($pdo);
$property = $propertyModel->getById((int) (isset($_GET['id']) ? $_GET['id'] : 0));

// On autorise la visualisation si :
// 1. Le bien est publié
// 2. L'utilisateur est un manager ou un agent
// 3. L'utilisateur est le propriétaire du bien
$canView = $property && (
    $property['status'] === 'published' || 
    (isLoggedIn() && in_array(currentUserRole(), ['manager', 'agent'])) ||
    (isLoggedIn() && (int)$property['owner_id'] === (int)$_SESSION['user_id'])
);

if (!$canView) {
    flash('error', 'Accès restreint ou propriété introuvable.');
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

    <div class="flex items-center justify-center slider-layout">
        <button id="prev" class="md:p-2 p-1 bg-black/30 md:mr-6 mr-2 rounded-full hover:bg-black/50 slider-nav-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <div class="w-full max-w-3xl overflow-hidden relative slider-viewport">
            <div class="flex transition-transform duration-500 ease-in-out slider-track" id="slider">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $img): ?>
                        <img src="<?= url('uploads/properties/' . $img['image_path']) ?>" class="w-full flex-shrink-0" alt="Photo">
                    <?php endforeach; ?>
                <?php else: ?>
                    <img src="<?= url('assets/img/default-property.jpg') ?>" class="w-full flex-shrink-0" alt="Pas d'image">
                <?php endif; ?>
            </div>
        </div>
        <button id="next" class="p-1 md:p-2 bg-black/30 md:ml-6 ml-2 rounded-full hover:bg-black/50 slider-nav-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>
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
