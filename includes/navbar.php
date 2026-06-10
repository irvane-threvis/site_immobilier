<nav class="navbar">
    <a href="<?= url() ?>" class="logo">
        <i class="fa-solid fa-building logo-icon"></i>
        <span>ImmoFaso</span>
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
