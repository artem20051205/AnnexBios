<?php
$rows = ['A', 'B', 'C', 'D', 'E'];
$seatsPerRow = 8;

require_once 'db.php';

$voorstellingId = (int) ($_GET['voorstelling'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT v.*, m.title
     FROM voorstelling v
     JOIN movies m ON m.movie_id = v.film_id
     WHERE v.voorstelling_id = ?"
);
$stmt->execute([$voorstellingId]);
$voorstelling = $stmt->fetch(PDO::FETCH_ASSOC); // одна строка или false

// уже забронированные кресла на этот сеанс
$stmt = $pdo->prepare("SELECT stoel FROM reservering WHERE voorstelling_id = ?");
$stmt->execute([$voorstellingId]);
$bezet = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="chairs">
        <?php if (!$voorstelling): ?>
            <h1>Voorstelling niet gevonden</h1>
            <a href="index.php">Kies een film</a>
        <?php else: ?>
            <h1><?= htmlspecialchars($voorstelling['title']) ?></h1>
            <p>
                <?= htmlspecialchars(date('d-m-Y', strtotime($voorstelling['datum']))) ?>
                om <?= htmlspecialchars(substr($voorstelling['begintijd'], 0, 5)) ?>
            </p>
            <h2>Kies je stoelen</h2>
            <form method="post" action="bevestiging.php">
                <input type="hidden" name="voorstelling_id" value="<?= (int) $voorstelling['voorstelling_id'] ?>">
                <div class="screen"></div>
                <div class="chair-grid">
                    <?php foreach ($rows as $row): ?>
                        <?php for ($seat = 1; $seat <= $seatsPerRow; $seat++): ?>
                            <?php $code = $row . $seat; ?>
                            <?php if (in_array($code, $bezet)): ?>
                                <span class="seat seat-body seat-taken">
                                    <img src="style/images/chairgray.png" alt="Bezet">
                                    <?php echo $code; ?>
                                </span>
                            <?php else: ?>
                                <label class="seat">
                                    <input type="checkbox" name="seats[]" value="<?php echo $code; ?>">
                                    <span class="seat-body">
                                        <img src="style/images/chair.png" alt="" class="chair-free">
                                        <img src="style/images/chairwhite.png" alt="" class="chair-selected">
                                        <?php echo $code; ?>
                                    </span>
                                </label>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="confirm">Bevestigen</button>
            </form>
        <?php endif; ?>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>
