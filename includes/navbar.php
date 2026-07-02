<?php
$unreadCount = 0;
if (isLoggedIn() && currentUserRole() === 'agent') {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
    $stmt->execute([$_SESSION['user_id']]);
    $unreadCount = (int) $stmt->fetchColumn();
}
?>
<nav class="navbar">
    <a href="<?= url() ?>" class="navbar-logo">
       <img src="<?= url('assets/images/logo.jpg') ?>" alt="logo">
    </a>

    <ul class="nav-links">
        <li><a href="<?= url() ?>">Accueil</a></li>
        <li><a href="<?= url('biens.php') ?>">Biens</a></li>
        <?php if (isLoggedIn()): ?>
            <li><a href="<?= url(getDashboardPath()) ?>">Espace</a></li>
            <?php if (currentUserRole() === 'client'): ?>
                <li><a href="<?= url('views/client/favorites.php') ?>">Favoris</a></li>
                <li><a href="<?= url('views/client/visits.php') ?>">Visites</a></li>
            <?php elseif (currentUserRole() === 'bailleur'): ?>
                <li><a href="<?= url('views/property/create.php') ?>">+ Annonce</a></li>
            <?php elseif (currentUserRole() === 'agent'): ?>
                <li><a href="<?= url('views/agent/validations.php') ?>">Valider</a></li>
                <li>
                    <a href="<?= url('views/notifications.php') ?>" title="Notifications">
                        <i class="fa-solid fa-bell"></i>
                        <?= $unreadCount > 0 ? "<span style='color:var(--danger); font-weight:bold;'>($unreadCount)</span>" : "" ?>
                    </a>
                </li>
            <?php elseif (currentUserRole() === 'manager'): ?>
                <li><a href="<?= url('views/manager/dashboard.php') ?>">Admin</a></li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>

    <div class="auth-buttons">
        <?php if (isLoggedIn()): ?>
           
            <a href="<?= url('deconnexion.php') ?>" class="btn-register">Déconnexion</a>
        <?php else: ?>
            <a href="<?= url('connexion.php') ?>" class="btn-login">Connexion</a>
            <a href="<?= url('inscription.php') ?>" class="btn-register">Inscription</a>
        <?php endif; ?>
    </div>
</nav>
