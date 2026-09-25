<?php
require_once 'db.php';

$voorstellingId = (int) ($_POST['voorstelling_id'] ?? 0);
$seats = $_POST['seats'] ?? [];

// проверяем ввод: пользователь может отправить что угодно, а не только наши кресла
$validSeats = [];
foreach ((array) $seats as $seat) {
    if (is_string($seat) && preg_match('/^[A-E][1-8]$/', $seat)) {
        $validSeats[] = $seat;
    }
}

// сохраняем бронь; INSERT IGNORE пропускает кресла, которые кто-то уже занял
$geboekt = [];
if ($voorstellingId > 0 && $validSeats) {
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
