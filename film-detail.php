<?php

require_once 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

$voorstellingen = [];

if ($movie) {
    $stmt = $pdo->prepare("
        SELECT * 
        FROM voorstelling 
        WHERE film_id = ? 
        ORDER BY datum, begintijd
    ");

    $stmt->execute([$id]);

    $voorstellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>

    <?php include 'includes/header.php'; ?>


    <?php if ($movie): ?>

        <div class="detail-container">

            <div class="detail-movie">

                <img
                    class="detail-poster"
                    src="<?= htmlspecialchars($movie['poster'] ?? 'style/images/Placeholder.png') ?>"
                    alt="Poster"
                >


                <div class="detail-info">

                    <h1>
                        <?= htmlspecialchars($movie['title']) ?>
                    </h1>


                    <p class="detail-description">
                        <?= htmlspecialchars($movie['description'] ?? '') ?>
                    </p>


                    <p>
                        <strong>Release date:</strong>
                        <?= htmlspecialchars($movie['release_date'] ?? '') ?>
                    </p>


                    <p>
                        <strong>Rating:</strong>
                        <?= htmlspecialchars($movie['imd_rating'] ?? '') ?>
                    </p>


                    <h2>
                        Voorstellingen
                    </h2>


                    <div class="showtimes">

                        <?php foreach ($voorstellingen as $v): ?>

                            <a href="bestellen.php?voorstelling=<?= $v['voorstelling_id'] ?>">

                                <?= htmlspecialchars($v['datum']) ?>

                                <br>

                                <?= htmlspecialchars($v['begintijd']) ?>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>


    <?php else: ?>

        <div class="detail-container">

            <h1>
                Unknown Film
            </h1>

        </div>

    <?php endif; ?>


    <?php include 'includes/footer.php'; ?>

</body>

</html>