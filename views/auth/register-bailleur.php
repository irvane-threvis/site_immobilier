<?php include ROOT_PATH . 'includes/header.php'; ?>
<?php include ROOT_PATH . 'includes/navbar.php'; ?>

<div class="auth-container">
    <form class="auth-form" action="<?= url('controllers/AuthController.php?action=registerBailleur') ?>" method="POST">
        <h2>Inscription Bailleur</h2>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="password" name="password" placeholder="Mot de passe" required minlength="6">
        <button type="submit" class="btn-primary">Valider</button>
        <p class="auth-links"><a href="<?= url('connexion.php') ?>">Déjà inscrit ? Connexion</a></p>
    </form>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
