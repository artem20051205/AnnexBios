<?php
require_once 'api.php';
require_once 'db.php';

$voorstellingId = (int) ($_POST['voorstelling_id'] ?? 0);
$seats = $_POST['seats'] ?? [];

// Invoer controleren: alleen stoelen A1 t/m E8
$validSeats = [];
foreach ((array) $seats as $seat) {
    if (is_string($seat) && preg_match('/^[A-E][1-8]$/', $seat)) {
        $validSeats[] = $seat;
    }
}

// Alleen boeken als de voorstelling bij onze bioscoop bestaat (API);
// INSERT IGNORE slaat stoelen over die al bezet zijn
$geboekt = [];
if ($voorstellingId > 0 && $validSeats && api_showtime($voorstellingId) !== null) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO reservering (voorstelling_id, stoel) VALUES (?, ?)");
    foreach ($validSeats as $seat) {
        $stmt->execute([$voorstellingId, $seat]);
        if ($stmt->rowCount() > 0) {
            $geboekt[] = $seat;
        }
    }
}
$bezet = array_diff($validSeats, $geboekt);
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="chairs">
        <?php if (empty($geboekt)): ?>
            <h1>Geen stoelen geboekt</h1>
        <?php else: ?>
            <h1>Je hebt geboekt:</h1>
            <p><?php echo htmlspecialchars(implode(', ', $geboekt)); ?></p>
        <?php endif; ?>

        <?php if ($bezet): ?>
            <p>Al bezet: <?php echo htmlspecialchars(implode(', ', $bezet)); ?></p>
        <?php endif; ?>

        <a href="bestellen.php?voorstelling=<?= $voorstellingId ?>">Terug</a>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
