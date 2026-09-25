<?php
$rows = ['A', 'B', 'C', 'D', 'E'];
$seatsPerRow = 8;
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>
    <poster>
        <img src="<?= htmlspecialchars($movie['poster'] ?? 'style/images/Placeholder.png') ?>" alt="Poster" width="200">
    </poster>
    <main class="chairs">
        <h1>Kies je stoelen</h1>
        <div class="screen"></div>
        <div class="chair-grid">
            <?php foreach ($rows as $row): ?>
                <?php for ($seat = 1; $seat <= $seatsPerRow; $seat++): ?>
                    <button type="button" aria-label="Stoel <?php echo $row . $seat; ?>"><?php echo $row . $seat; ?>
                        <img src="style/images/chair.png" alt="">
                    </button>
                <?php endfor; ?>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>