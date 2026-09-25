<?php

require_once 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC); // одна строка или false
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <?php if ($movie): ?>
        <h1><?= htmlspecialchars($movie['title']) ?></h1>
        <p><?= htmlspecialchars($movie['description'] ?? '') ?></p>
        <p>release date: <?= htmlspecialchars($movie['release_date'] ?? '') ?></p>
        <p>Raiting: <?= htmlspecialchars($movie['imd_rating'] ?? '') ?></p>
        <poster>
            <img src="<?= htmlspecialchars($movie['poster'] ?? 'style/images/Placeholder.png') ?>" alt="Poster" width="200">
        </poster>
    <?php else: ?>
        <h1>Unknown Film</h1>
    <?php endif; ?>
    <button onclick="window.location.href='bestellen.php?id=<?= htmlspecialchars($movie['movie_id'] ?? '') ?>'" >Bestellen </button>
        <?php include 'includes/footer.php'; ?>
</body>

</html>