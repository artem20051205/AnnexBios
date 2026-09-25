<?php

require_once 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC); // одна строка или false

$voorstellingen = [];
if ($movie) {
    $stmt = $pdo->prepare("SELECT * FROM voorstelling WHERE film_id = ? ORDER BY datum, begintijd");
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
        <h1><?= htmlspecialchars($movie['title']) ?></h1>
        <p><?= htmlspecialchars($movie['description'] ?? '') ?></p>
        <p>release date: <?= htmlspecialchars($movie['release_date'] ?? '') ?></p>
        <p>Raiting: <?= htmlspecialchars($movie['imd_rating'] ?? '') ?></p>
        <poster>
            <img src="<?= htmlspecialchars($movie['poster'] ?? 'style/images/Placeholder.png') ?>" alt="Poster" width="200">
        </poster>

        <h2>Voorstellingen</h2>
        <?php foreach ($voorstellingen as $v): ?>
            <a href="bestellen.php?voorstelling=<?= $v['voorstelling_id'] ?>">
                <?= htmlspecialchars($v['datum'] . ' ' . $v['begintijd']) ?>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <h1>Unknown Film</h1>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
