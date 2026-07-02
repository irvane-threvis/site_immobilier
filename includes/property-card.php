<?php
// Ensure $property is defined (e.g., when included in a loop)
if (!isset($property)) {
    return;
}
$defaultImageUrl = url('assets/img/default-property.jpg'); // A local default image
$images = !empty($property['images']) ? $property['images'] : [];


// Image de couverture : la première image locale ou l'image par défaut
$coverImage = !empty($images) 
    ? url('uploads/properties/' . trim($images[0])) 
    : $defaultImageUrl;

$priceLabel = number_format($property['prix'], 0, ',', ' ') . ' FCFA'; // Corrected variable name
if ($property['option_type'] === 'location') {
    $priceLabel .= '/mois';
}

$typeLabel = ucfirst($property['type']);
$optionLabel = $property['option_type'] === 'vente' ? 'Vente' : 'Location';
?>
<article class="property-card" data-property-id="<?= $property['id'] ?>">
    <a href="<?= url('views/property/detail.php?id=' . $property['id']) ?>" class="card-image-link">
        <img src="<?= htmlspecialchars($coverImage) ?>" alt="<?= htmlspecialchars($property['titre']) ?>" 
             loading="lazy" class="card-cover-image">
        <span class="card-badge"><?= $optionLabel ?></span>
    </a>
    <div class="property-content">
        <span class="card-type"><?= $typeLabel ?></span>
        <h3>
            <a href="<?= url('views/property/detail.php?id=' . $property['id']) ?>">
                <?= htmlspecialchars($property['titre']) ?>
            </a>
        </h3>
        <p class="card-location"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($property['ville']) ?></p>
        <p class="card-price"><?= $priceLabel ?></p>
        <a href="<?= url('views/property/detail.php?id=' . $property['id']) ?>" class="card-btn">Voir</a>
    </div>
</article>
