<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservering_id'])) {
    $deleteStmt = $pdo->prepare('DELETE FROM reservering WHERE reservering_id = ?');
    $deleteStmt->execute([$_POST['delete_reservering_id']]);
    header('Location: reservering.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
$reserveringStmt = $pdo->query("SELECT * FROM reservering");    
foreach ($reserveringStmt as $reservering) {
    echo "<div>";
    echo "<p>Reservering ID: " . htmlspecialchars($reservering['reservering_id']) . "</p>";
    echo "<p>Stoel: " . htmlspecialchars($reservering['stoel']) . "</p>";
    echo "<p>Voorstelling ID: " . htmlspecialchars($reservering['voorstelling_id']) . "</p>";
    ?>
    <form method="post">
        <input type="hidden" name="delete_reservering_id" value="<?= (int) $reservering['reservering_id'] ?>">
        <button type="submit">Verwijderen</button>
    </form>
<?php

    echo "</div><hr>";
}






?><a href="add_reservering.php">ADD Reservering</a>




 
</body>
</html>