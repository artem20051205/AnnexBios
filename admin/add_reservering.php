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
    <link rel="icon" type="image/png" href="../style/images/favicon.png">
    <link rel="stylesheet" href="../style/output.css?v=<?= filemtime(__DIR__ . '/../style/output.css') ?>">
    <link rel="stylesheet" href="../style/style.css?v=<?= filemtime(__DIR__ . '/../style/style.css') ?>">
</head>
<body>
<?php $base = '../'; include '../includes/header.php'; ?>

<main class="chairs">
 <h1>Reservering toevoegen</h1>
 <form action="add_reservering.php" method="post">
    <div class="customer">
     <label for="voorstelling_id">Voorstelling ID:</label>
    <input type="number" id="voorstelling_id" name="voorstelling_id" required>
     <label for="stoel">Stoelnummer:</label>
    <input type="text" id="stoel" name="stoel" required>
    </div>
     <input type="hidden" name="add_reservering" value="1">
    <button type="submit" class="confirm">Toevoegen</button>
 </form>
 <a class="details-button" href="reservering.php">Terug</a>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>