<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';

$propertyModel = new Property($pdo);

$filters = array(
    'q' => trim(isset($_GET['q']) ? $_GET['q'] : ''),
    'type' => isset($_GET['type']) ? $_GET['type'] : '',
    'option_type' => isset($_GET['option_type']) ? $_GET['option_type'] : '',
    'ville' => trim(isset($_GET['ville']) ? $_GET['ville'] : ''),
    'prix_min' => isset($_GET['prix_min']) ? $_GET['prix_min'] : '',
    'prix_max' => isset($_GET['prix_max']) ? $_GET['prix_max'] : ''
);
$page = max(1, (int) (isset($_GET['page']) ? $_GET['page'] : 1));
$result = $propertyModel->search($filters, $page, 12);
$properties = $result['items'];
$totalPublished = $propertyModel->countPublished();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <div class="section-head">
        <h1>Biens disponibles</h1>
        <span class="count-badge"><?= $totalPublished ?> annonce<?= $totalPublished > 1 ? 's' : '' ?></span>
    </div>

    <form class="filter-bar" method="GET">
        <input type="text" name="q" placeholder="Rechercher..." value="<?= htmlspecialchars($filters['q']) ?>">
        <select name="type">
            <option value="">Type</option>
            <?php foreach (array('villa', 'terrain', 'appartement', 'commerce', 'immeuble') as $t): ?>
                <option value="<?= $t ?>" <?= $filters['type'] === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="option_type">
            <option value="">Tout</option>
            <option value="vente" <?= $filters['option_type'] === 'vente' ? 'selected' : '' ?>>Vente</option>
            <option value="location" <?= $filters['option_type'] === 'location' ? 'selected' : '' ?>>Location</option>
        </select>
        <button type="submit"><i class="fa-solid fa-search"></i></button>
    </form>

    <?php if (empty($properties)): ?>
        <p class="empty-msg">Aucun bien trouvé.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($properties as $property): ?>
                <?php include ROOT_PATH . 'includes/property-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($result['totalPages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $result['totalPages']; $i++): ?>
                    <?php
                    $query = array_merge($filters, array('page' => $i));
                    $queryString = http_build_query(array_filter($query, function ($v) { return $v !== ''; }));
                    ?>
                    <a href="?<?= $queryString ?>" class="<?= $i === $result['page'] ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
