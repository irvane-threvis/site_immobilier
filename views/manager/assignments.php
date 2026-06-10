<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('manager');

$clients = $pdo->query("SELECT * FROM users WHERE role = 'client'")->fetchAll();
$agents = $pdo->query("SELECT * FROM users WHERE role = 'agent'")->fetchAll();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <form class="property-form" action="<?= url('controllers/ManagerController.php?action=assign') ?>" method="POST">
        <h2>Affecter un client à un agent</h2>

        <?php if (empty($clients) || empty($agents)): ?>
            <p class="empty-msg">Il faut au moins un client et un agent pour effectuer une affectation.</p>
        <?php else: ?>
            <select name="client_id" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom'] . ' ' . $client['prenom']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="agent_id" required>
                <?php foreach ($agents as $agent): ?>
                    <option value="<?= $agent['id'] ?>"><?= htmlspecialchars($agent['nom'] . ' ' . $agent['prenom']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Affecter</button>
        <?php endif; ?>
    </form>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
