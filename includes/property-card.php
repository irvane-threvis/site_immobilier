<?php
$imgSrc = !empty($property['image_url'])
    ? $property['image_url']
    : 'https://picsum.photos/seed/immo' . (int) $property['id'] . '/600/400';

$priceLabel = number_format($property['prix'], 0, ',', ' ') . ' FCFA';
if ($property['option_type'] === 'location') {
    $priceLabel .= '/mois';
}

$typeLabel = ucfirst($property['type']);
$optionLabel = $property['option_type'] === 'vente' ? 'Vente' : 'Location';
?>
<article class="property-card">
    <a href="<?= url('views/property/detail.php?id=' . $property['id']) ?>" class="card-image-link">
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($property['titre']) ?>" loading="lazy">
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
