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
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
 
    <header>
        <a href="index.html">
            <img src="style/images/logo.png" alt="Logo" width="300" class="logo">
        </a>

        <nav>
            <a href="index.html">Home</a>
            <a href="films.php">Filmagenda</a>
            <a href="film-detail.html">Film detail</a>
            <a href="bestellen.html">Bestellen</a>
            <a href="admin.html">Admin</a>
        </nav>
    </header>
    <div class="dunepic">
        <img src="style/images/dune.png" alt="Dune" width="1280x1024" class="dune">
    </div>
    <div class="arraybackground">
        <div class="film-intro">
            <h2>Welkom bij AnnexBios Bilthoven</h2>
            <p>Ontdek de laatste films in comfort</p>
            <button onclick="window.location.href='films.php'">Film Agenda Bekijken</button>
        </div>

        <?php foreach ($movies as $movie): ?>
          <div class="movie-item">
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
        
            <p>
                <a href="film-detail.php?id=<?= htmlspecialchars($movie['movie_id'] ?? '') ?>">
                    Bekijk details
                </a>
            </p>
            <hr>
        </div>

    <?php endforeach; ?>
    </div>


    
    <footer>
        <p>© 2026 AnnexBios Bilthoven</p>

        <div class="footer-links">
            <a href="index.html">Home</a>
            <a href="films.php">Filmagenda</a>
            <a href="bestellen.html">Bestellen</a>
        </div>
    </footer>

</body>

</html>