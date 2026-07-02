<?php include ROOT_PATH . 'includes/header.php'; ?>
<?php include ROOT_PATH . 'includes/navbar.php'; ?>

<section class="hero hero-compact">
    <!-- Vidéo de fond -->
    <video class="hero-video" autoplay muted loop playsinline>
        <source src="assets/images/univers.mp4" type="video/mp4">
    </video>

    <div class="hero-content">
        <h1>L'Alliance Immobilière</h1>
        <p>Acheter • Louer • Investir au Burkina Faso</p>
        <form class="search-box" action="<?= url('biens.php') ?>" method="GET">
            <input type="text" name="q" placeholder="Ville, quartier, type de bien...">
            <button type="submit"><i class="fa-solid fa-search"></i> Chercher</button>
        </form>
    </div>
</section>

<section class="listings-home">
    <div class="section-head">
        <h2>Biens disponibles</h2>
        <a href="<?= url('biens.php') ?>" class="link-more">Tout voir →</a>
    </div>

    <div class="filter-chips">
        <a href="<?= url('biens.php') ?>" class="chip active">Tous</a>
        <a href="<?= url('biens.php?option_type=location') ?>" class="chip">Location</a>
        <a href="<?= url('biens.php?option_type=vente') ?>" class="chip">Vente</a>
        <a href="<?= url('biens.php?type=villa') ?>" class="chip">Villas</a>
        <a href="<?= url('biens.php?type=appartement') ?>" class="chip">Apparts</a>
        <a href="<?= url('biens.php?type=terrain') ?>" class="chip">Terrains</a>
    </div>

    <div class="cards">
        <?php if (empty($latestProperties)): ?>
            <p class="empty-msg">Aucune annonce pour le moment.</p>
        <?php else: ?>
            <?php foreach ($latestProperties as $property): ?>
                <?php include ROOT_PATH . 'includes/property-card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include ROOT_PATH . 'includes/footer.php'; ?>
