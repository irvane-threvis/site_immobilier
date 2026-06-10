<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('agent');

$stmt = $pdo->prepare(
    "SELECT c.nom AS client_nom, c.prenom AS client_prenom
     FROM client_agent ca
     JOIN users c ON ca.client_id = c.id
     WHERE ca.agent_id = ?"
);
$stmt->execute([$_SESSION['user_id']]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Mes clients</h1>

    <?php if (empty($clients)): ?>
        <p class="empty-msg">Aucun client affecté pour le moment.</p>
    <?php else: ?>
        <table class="visit-table">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
            </tr>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['client_nom']) ?></td>
                    <td><?= htmlspecialchars($client['client_prenom']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
