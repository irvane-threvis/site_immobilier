<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
requireRole('manager');

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Gestion des utilisateurs</h1>

    <table class="visit-table">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= htmlspecialchars($user['status']) ?></td>
                <td>
                    <?php if ($user['role'] !== 'manager'): ?>
                        <a class="danger-btn" href="<?= url('controllers/ManagerController.php?action=deleteUser&id=' . $user['id']) ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
