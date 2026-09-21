<?php

require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM movies ORDER BY movie_id");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
</head>
<body>
<?php foreach ($movies as $movie): ?>
      <div>
        <h2><?= htmlspecialchars($movie['title']) ?></h2>

        <p>
            <?= htmlspecialchars($movie['description'] ?? '') ?>
        </p>

        <p>
            release date:
            <?= htmlspecialchars($movie['release_date'] ?? '') ?>
        </p>

        <p>
            Raiting:
            <?= htmlspecialchars($movie['imd_rating'] ?? '') ?>
        </p>

        <hr>
    </div>

<?php endforeach; ?>
        
</body>
</html>