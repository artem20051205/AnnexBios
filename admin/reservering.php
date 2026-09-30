<?php
require_once '../db.php';
session_start();

// niet ingelogd? dan terug naar de login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// reservering verwijderen als er op de knop is gedrukt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservering_id'])) {
    $deleteStmt = $pdo->prepare('DELETE FROM reservering WHERE reservering_id = ?');
    $deleteStmt->execute([$_POST['delete_reservering_id']]);
    // redirect, anders stuurt een refresh het formulier nog een keer
    header('Location: reservering.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserveringen</title>
    <link rel="icon" type="image/png" href="../style/images/favicon.png">
    <link rel="stylesheet" href="../style/output.css?v=<?= filemtime(__DIR__ . '/../style/output.css') ?>">
    <link rel="stylesheet" href="../style/style.css?v=<?= filemtime(__DIR__ . '/../style/style.css') ?>">
</head>
<body>
<?php $base = '../'; include '../includes/header.php'; ?>

<main class="chairs">
<h1>Reserveringen</h1>
<a class="details-button" href="admin.php">Terug</a>
<?php
// alle reserveringen, elk met een knop om te verwijderen
$reserveringStmt = $pdo->query("SELECT * FROM reservering");    
foreach ($reserveringStmt as $reservering) {
    echo "<div>";
    echo "<p>Reservering ID: " . htmlspecialchars($reservering['reservering_id']) . "</p>";
    echo "<p>Stoel: " . htmlspecialchars($reservering['stoel']) . "</p>";
    echo "<p>Voorstelling ID: " . htmlspecialchars($reservering['voorstelling_id']) . "</p>";
    echo "<p>Naam: " . htmlspecialchars($reservering['naam']) . "</p>";
    echo "<p>E-mail: " . htmlspecialchars($reservering['email']) . "</p>";
    ?>
    <form method="post">
        <input type="hidden" name="delete_reservering_id" value="<?= (int) $reservering['reservering_id'] ?>">
        <button type="submit" class="details-button">Verwijderen</button>
    </form>
<?php

    echo "</div><hr>";
}






?><a class="details-button" href="add_reservering.php">Reservering toevoegen</a>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>