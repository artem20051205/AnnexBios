<?php
require_once 'api.php';
require_once 'db.php';

// gegevens uit het formulier van bestellen.php
$voorstellingId = (int) ($_POST['voorstelling_id'] ?? 0);
$seats = $_POST['seats'] ?? [];
// is_string omdat iemand ook een array kan sturen in plaats van tekst
$naam = is_string($_POST['naam'] ?? null) ? trim($_POST['naam']) : '';
$email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';

// check of de stoelen kloppen (A1 t/m E8)
$validSeats = [];
foreach ((array) $seats as $seat) {
    if (is_string($seat) && preg_match('/^[A-E][1-8]$/', $seat)) {
        $validSeats[] = $seat;
    }
}

// naam niet leeg, e-mail moet kloppen en niet langer dan in de database past
$klantOk = $naam !== '' && mb_strlen($naam) <= 100
    && strlen($email) <= 255 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

$geboekt = [];
$bezet = [];
// alleen opslaan als alles klopt en de voorstelling echt bestaat
if ($klantOk && $voorstellingId > 0 && $validSeats && api_showtime($voorstellingId) !== null) {
    // stoel al bezet? dan slaat INSERT IGNORE hem over
    $stmt = $pdo->prepare("INSERT IGNORE INTO reservering (voorstelling_id, stoel, naam, email) VALUES (?, ?, ?, ?)");
    foreach ($validSeats as $seat) {
        $stmt->execute([$voorstellingId, $seat, $naam, $email]);
        // rowCount 0 = niet opgeslagen
        if ($stmt->rowCount() > 0) {
            $geboekt[] = $seat;
        }
    }
    // wat niet gelukt is, was al bezet
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
