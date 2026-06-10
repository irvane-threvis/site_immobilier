<?php include ROOT_PATH . 'includes/header.php'; ?>
<?php include ROOT_PATH . 'includes/navbar.php'; ?>

<div class="auth-container">
    <form class="auth-form" action="<?= url('controllers/AuthController.php?action=login') ?>" method="POST">
        <h2>Connexion</h2>
        
        <input type="email" name="email" placeholder="Email" required autofocus>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="btn-primary">Soumettre</button>
        <p class="auth-links">
            Pas de compte ? <a href="<?= url('inscription.php') ?>">Inscription</a>
        </p>
    </form>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
