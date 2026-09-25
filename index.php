<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM movies ORDER BY movie_id");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$heroImage = 'style/images/dune.png';
$heroStmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_name = :name LIMIT 1");
$heroStmt->execute([':name' => 'hero_image']);
$heroRow = $heroStmt->fetch(PDO::FETCH_ASSOC);

if ($heroRow && !empty($heroRow['setting_value'])) {
    $heroImage = $heroRow['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>

    <?php include 'includes/header.php'; ?>

    <!-- Dune afbeelding -->
    <div class="dunepic">

        <img
            src="<?= htmlspecialchars($heroImage) ?>"
            alt="Dune"
            class="dune"
        >

        <!-- Tekst op de afbeelding -->
        <div class="film-intro">

            <h2>Welkom bij AnnexBios Bilthoven</h2>

            <p>Ontdek de laatste films in comfort</p>

            <button onclick="window.location.href='films.php'">
                Film Agenda Bekijken
            </button>

        </div>

    </div>


    <!-- Films uit database -->
    <div class="arraybackground">

        <?php foreach ($movies as $movie): ?>

            <div class="movie-item">

                <h2>
                    <?= htmlspecialchars($movie['title']) ?>
                </h2>

                <p>
                    <?= htmlspecialchars($movie['description'] ?? '') ?>
                </p>

                <p>
                    Release date:
                    <?= htmlspecialchars($movie['release_date'] ?? '') ?>
                </p>

                <p>
                    Rating:
                    <?= htmlspecialchars($movie['imd_rating'] ?? '') ?>
                </p>

                <p>
                    <a href="film-detail.php?id=<?= htmlspecialchars($movie['movie_id'] ?? '') ?>">
                        Bekijk details
                    </a>
                </p>

                <p>
                    <img
                        src="<?= htmlspecialchars($movie['poster'] ?? 'style/images/Placeholder.png') ?>"
                        alt="Poster"
                        width="200"
                    >
                </p>

                <hr>

            </div>

        <?php endforeach; ?>

    </div>

    <?php include 'includes/footer.php'; ?>

</body>

</html>