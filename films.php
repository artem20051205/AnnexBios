<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM movies ORDER BY movie_id");
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
</head>
<body>
<?php foreach ($films as $film): ?>
    <div>
        <h2><?= htmlspecialchars($film['title']) ?></h2>

        <p>
            <?= htmlspecialchars($film['description']) ?>
        </p>

        <p>
            release date:
            <?= htmlspecialchars($film['release_date']) ?>
        </p>

        <p>
            Rating:
            <?= htmlspecialchars($film['imd_rating'] ?? '') ?>
        </p>

        <a href="film-detail.php?id=<?= (int) $film['movie_id'] ?>">
            Bekijk details
        </a>

        <hr>
    </div>

<?php endforeach; ?>
        
</body>
</html>