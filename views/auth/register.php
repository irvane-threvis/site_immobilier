<?php include ROOT_PATH . 'includes/header.php'; ?>
<?php include ROOT_PATH . 'includes/navbar.php'; ?>

<div class="auth-container">
    <form class="auth-form auth-form-wide" action="<?= url('controllers/AuthController.php?action=register') ?>" method="POST">
        <h2>Inscription</h2>
      

        <fieldset class="role-choice">
            <legend>Je m'inscris en tant que :</legend>

            <label class="role-option <?= $defaultRole === 'client' ? 'selected' : '' ?>">
                <input type="radio" name="role" value="client" <?= $defaultRole === 'client' ? 'checked' : '' ?> required>
                <span class="role-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <span class="role-text">
                    <strong>Client</strong>
                    <small>Je cherche un bien à louer ou acheter. Je veux visiter des maisons.</small>
                </span>
            </label>

            <label class="role-option <?= $defaultRole === 'bailleur' ? 'selected' : '' ?>">
                <input type="radio" name="role" value="bailleur" <?= $defaultRole === 'bailleur' ? 'checked' : '' ?> required>
                <span class="role-icon"><i class="fa-solid fa-house-chimney"></i></span>
                <span class="role-text">
                    <strong>Bailleur</strong>
                    <small>J'ai un bien à mettre en location ou en vente.</small>
                </span>
            </label>
        </fieldset>

        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="password" name="password" placeholder="Mot de passe" minlength="6" required>

        <button type="submit" class="btn-primary">Valider</button>

        <p class="auth-links">
            Déjà inscrit ? <a href="<?= url('connexion.php') ?>">Se connecter </a>
        </p>
      
    </form>
</div>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
