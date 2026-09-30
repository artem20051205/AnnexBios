<?php
require_once '../db.php';
session_start();

// niet ingelogd? dan terug naar de login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// huidige hero afbeelding ophalen, dune.png als er nog niks is
$heroImage = 'style/images/dune.png';
$heroStmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_name = :name LIMIT 1");
$heroStmt->execute([':name' => 'hero_image']);
$heroRow = $heroStmt->fetch(PDO::FETCH_ASSOC);

if ($heroRow && !empty($heroRow['setting_value'])) {
    $heroImage = $heroRow['setting_value'];
}

// nieuwe afbeelding opslaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hero_image'])) {
    $newHeroImage = trim($_POST['hero_image']);

    if ($newHeroImage === '') {
        $error = 'Vul een afbeelding pad of URL in.';
    } else {
        // bestaat hero_image al, dan wordt hij aangepast, anders nieuw toegevoegd
        $update = $pdo->prepare(
            "INSERT INTO site_settings (setting_name, setting_value) VALUES (:name, :value)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        );

        $update->execute([
            ':name' => 'hero_image',
            ':value' => $newHeroImage,
        ]);

        $heroImage = $newHeroImage;
        $success = 'De homepage-afbeelding is succesvol aangepast.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" type="image/png" href="../style/images/favicon.png">
    <link rel="stylesheet" href="../style/output.css?v=<?= filemtime(__DIR__ . '/../style/output.css') ?>">
    <link rel="stylesheet" href="../style/style.css?v=<?= filemtime(__DIR__ . '/../style/style.css') ?>">
</head>
<body>
    <?php $base = '../'; include '../includes/header.php'; ?>

    <main class="chairs">
        <h1>Welkom in het admin panel</h1>

        <?php if ($error !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="customer">
                <label for="hero_image">Homepage afbeelding:</label>
                <input
                    type="text"
                    id="hero_image"
                    name="hero_image"
                    value="<?= htmlspecialchars($heroImage) ?>"
                >
            </div>
            <button type="submit" class="confirm">Opslaan</button>
        </form>

        <h2>Preview:</h2>
        <!-- ../ ervoor omdat we in de admin map zitten -->
        <img src="../<?= htmlspecialchars($heroImage) ?>" alt="Homepage preview" style="max-width: 500px; margin: 10px auto;">

        <a class="details-button" href="reservering.php">Reserveringen</a>
        <a class="details-button" href="api-test.php">API debug</a>

        <form method="post" action="logout.php">
            <button type="submit" class="details-button">Logout</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
