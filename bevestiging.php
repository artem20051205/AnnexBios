<?php
require_once 'api.php';
require_once 'db.php';

$voorstellingId = (int) ($_POST['voorstelling_id'] ?? 0);
$seats = $_POST['seats'] ?? [];
$naam = is_string($_POST['naam'] ?? null) ? trim($_POST['naam']) : '';
$email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';

// Invoer controleren: alleen stoelen A1 t/m E8
$validSeats = [];
foreach ((array) $seats as $seat) {
    if (is_string($seat) && preg_match('/^[A-E][1-8]$/', $seat)) {
        $validSeats[] = $seat;
    }
}

// Naam en e-mail zijn verplicht, zodat de medewerker weet van wie de reservering is
$klantOk = $naam !== '' && mb_strlen($naam) <= 100
    && strlen($email) <= 255 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

// Alleen boeken als de voorstelling bij onze bioscoop bestaat (API);
// INSERT IGNORE slaat stoelen over die al bezet zijn
$geboekt = [];
$bezet = [];
if ($klantOk && $voorstellingId > 0 && $validSeats && api_showtime($voorstellingId) !== null) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO reservering (voorstelling_id, stoel, naam, email) VALUES (?, ?, ?, ?)");
    foreach ($validSeats as $seat) {
        $stmt->execute([$voorstellingId, $seat, $naam, $email]);
        if ($stmt->rowCount() > 0) {
            $geboekt[] = $seat;
        }
    }
    $bezet = array_diff($validSeats, $geboekt);
}
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="chairs">
        <?php if (!$klantOk): ?>
            <h1>Geen stoelen geboekt</h1>
            <p>Vul je naam en een geldig e-mailadres in.</p>
        <?php elseif (empty($geboekt)): ?>
            <h1>Geen stoelen geboekt</h1>
        <?php else: ?>
            <h1>Bedankt, <?= htmlspecialchars($naam) ?>!</h1>
            <p>Je hebt geboekt: <?php echo htmlspecialchars(implode(', ', $geboekt)); ?></p>
        <?php endif; ?>

        <?php if ($bezet): ?>
            <p>Al bezet: <?php echo htmlspecialchars(implode(', ', $bezet)); ?></p>
        <?php endif; ?>

        <a href="bestellen.php?voorstelling=<?= $voorstellingId ?>">Terug</a>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
