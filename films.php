<?php
require_once 'api.php';

$films = api_movies();
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
<?php include 'includes/header.php'; ?>

<main class="agenda">
    <div class="agenda-header">
        <div>
            <p class="agenda-label">AnnexBios Bilthoven</p>
            <h1 class="agenda-title">Filmagenda</h1>
        </div>
        <span class="agenda-count">
            <?= count($films) ?> films in de agenda
        </span>
    </div>

    <?php if ($films): ?>
        <div class="agenda-grid">
            <?php foreach ($films as $film): ?>
                <article class="film-card">
                    <div class="film-card-poster">
                        <img
                            src="<?= htmlspecialchars($film['posterPath']) ?>"
                            onerror="this.onerror=null; this.src='style/images/Placeholder.png'"
                            alt="Poster van <?= htmlspecialchars($film['title']) ?>"
                        >
                        <span class="film-card-rating">
                            <?= htmlspecialchars($film['imdRating'] ?? '-') ?> / 10
                        </span>
                    </div>

                    <div class="film-card-body">
                        <h2 class="film-card-title">
                            <?= htmlspecialchars($film['title']) ?>
                        </h2>

                        <p class="film-card-description">
                            <?= htmlspecialchars($film['description'] ?? 'Geen beschrijving beschikbaar.') ?>
                        </p>

                        <div class="film-card-footer">
                            <p class="film-card-release">
                                Release: <?= htmlspecialchars($film['releaseDate'] ?? '-') ?>
                            </p>
                            <a
                                href="film-detail.php?id=<?= (int) $film['movieId'] ?>"
                                class="film-card-button"
                            >
                                Bekijk film
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="agenda-empty">
            Er zijn momenteel geen films beschikbaar.
        </p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>