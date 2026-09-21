<?php

require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM movies");

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
    <?php 
    echo "SELECT * FROM movies WHERE title = 'Dune'";
    
    
    
    ?>
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
<p>Welkom bij AnnexBios Bilthoven</p>
<p1>Ontdek de laatste films in comfort</p1>
<button onclick="window.location.href='films.php'">Film Agenda Bekijken</button>


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
