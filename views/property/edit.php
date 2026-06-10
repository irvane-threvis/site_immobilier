<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';
requireRole('bailleur');

$propertyModel = new Property($pdo);
$property = $propertyModel->getById((int) ($_GET['id'] ?? 0));

if (!$property || (int) $property['owner_id'] !== (int) $_SESSION['user_id']) {
    flash('error', 'Annonce introuvable.');
    redirect('views/bailleur/dashboard.php');
}

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="property-form-container page-content">
    <form class="property-form" action="<?= url('controllers/PropertyController.php?action=update') ?>" method="POST" enctype="multipart/form-data">
        <h2>Modifier l'annonce</h2>
        <input type="hidden" name="id" value="<?= $property['id'] ?>">

        <input type="text" name="titre" value="<?= htmlspecialchars($property['titre']) ?>" required>

        <select name="type" required>
            <?php foreach (['villa', 'terrain', 'appartement', 'commerce', 'immeuble'] as $t): ?>
                <option value="<?= $t ?>" <?= $property['type'] === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="usage_type" required>
            <?php foreach (['residence', 'bureau', 'commerce', 'agriculture'] as $u): ?>
                <option value="<?= $u ?>" <?= $property['usage_type'] === $u ? 'selected' : '' ?>><?= ucfirst($u) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="option_type" required>
            <option value="vente" <?= $property['option_type'] === 'vente' ? 'selected' : '' ?>>Vente</option>
            <option value="location" <?= $property['option_type'] === 'location' ? 'selected' : '' ?>>Location</option>
        </select>

        <input type="number" name="superficie" value="<?= htmlspecialchars($property['superficie']) ?>" step="0.01" required>
        <input type="number" name="prix" value="<?= htmlspecialchars($property['prix']) ?>" required>
        <input type="text" name="ville" value="<?= htmlspecialchars($property['ville']) ?>" required>
        <textarea name="adresse" required><?= htmlspecialchars($property['adresse']) ?></textarea>
        <textarea name="description" required><?= htmlspecialchars($property['description']) ?></textarea>
        <label>Ajouter de nouvelles photos </label>
        <input type="file" name="photos[]" multiple accept="image/*">
        <button type="submit">Enregistrer</button>
    </form>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
