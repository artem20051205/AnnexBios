<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reservering'])) {
    $addStmt = $pdo->prepare('INSERT INTO reservering (voorstelling_id, stoel) VALUES (?, ?)');
    $addStmt->execute([$_POST['voorstelling_id'], $_POST['stoel']]);
    header('Location: reservering.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservering toevoegen</title>
</head>
<body>
 <form action="add_reservering.php" method="post">
     <label for="voorstelling_id">Voorstelling ID:</label>
    <input type="number" id="voorstelling_id" name="voorstelling_id" required>
     <label for="stoel">Stoelnummer:</label>
    <input type="text" id="stoel" name="stoel" required>
     <input type="hidden" name="add_reservering" value="1">
    <button type="submit">Toevoegen</button>
 </form>
</body>
</html>