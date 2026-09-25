<?php
$seats = $_POST['seats'] ?? [];

// проверяем ввод: пользователь может отправить что угодно, а не только наши кресла
$validSeats = [];
foreach ($seats as $seat) {
    if (is_string($seat) && preg_match('/^[A-E][1-8]$/', $seat)) {
        $validSeats[] = $seat;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="chairs">
        <?php if (empty($validSeats)): ?>
            <h1>Geen stoelen gekozen</h1>
            <a href="bestellen.php">Terug</a>
        <?php else: ?>
            <h1>Je hebt gekozen:</h1>
            <p><?php echo htmlspecialchars(implode(', ', $validSeats)); ?></p>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
