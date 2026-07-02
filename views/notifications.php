<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}
require_once ROOT_PATH . 'includes/bootstrap.php';
requireLogin();

// Seuls les agents (ou les rôles recevant des notifications) devraient accéder ici
if (currentUserRole() !== 'agent') {
    redirectByRole(currentUserRole());
}

// Récupérer l'historique avant de marquer comme lu pour identifier visuellement les nouvelles
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Marquer comme lues pour la prochaine visite
$stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE");
$stmt->execute([$_SESSION['user_id']]);

include ROOT_PATH . 'includes/header.php';
include ROOT_PATH . 'includes/navbar.php';
?>

<div class="page-content">
    <h1>Mes Notifications</h1>

    <div class="notifications-list">
        <?php if (empty($notifications)): ?>
            <p class="empty-msg">Vous n'avez aucune notification pour le moment.</p>
        <?php else: ?>
            <?php foreach ($notifications as $n): ?>
                <div class="notification-item <?= $n['is_read'] ? '' : 'unread' ?>">
                    <div class="notification-icon">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div class="notification-body">
                        <p class="notification-message"><?= htmlspecialchars($n['message']) ?></p>
                        <span class="notification-time">
                            <i class="fa-regular fa-clock"></i> 
                            <?= date('d/m/Y H:i', strtotime($n['created_at'])) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>