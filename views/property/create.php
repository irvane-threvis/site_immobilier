<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
requireRole('bailleur');

$propertyModel = new Property($pdo);
if (!$propertyModel->canSubmit($_SESSION['user_id'])) {
    flash('warning', 'Vous avez atteint votre limite maximale de 5 annonces.');
    redirect(getDashboardPath());
    exit;
}

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="property-form-container page-content">
    <form class="property-form" action="<?= url('controllers/PropertyController.php?action=create') ?>" method="POST" enctype="multipart/form-data">
        <h2>Ajouter une propriété</h2>

        <input type="text" name="titre" placeholder="Titre" required>

        <select name="type" required>
            <option value="villa">Villa</option>
            <option value="terrain">Terrain</option>
            <option value="appartement">Appartement</option>
            <option value="commerce">Commerce</option>
            <option value="immeuble">Immeuble</option>
        </select>

        <select name="usage_type" required>
            <option value="residence">Résidence</option>
            <option value="bureau">Bureau</option>
            <option value="commerce">Commerce</option>
            <option value="agriculture">Agriculture</option>
        </select>

        <select name="option_type" required>
            <option value="vente">Vente</option>
            <option value="location">Location</option>
        </select>

        <input type="number" name="superficie" placeholder="Superficie (m²)" step="0.01" required>
        <input type="number" name="prix" placeholder="Prix (FCFA)" required>
        <input type="text" name="ville" placeholder="Ville" required>
        <textarea name="adresse" placeholder="Adresse" required></textarea>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="file" name="photos[]" multiple accept="image/*" required>
        <button type="submit">Soumettre pour validation</button>
    </form>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
