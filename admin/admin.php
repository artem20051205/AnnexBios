<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

$heroImage = 'style/images/dune.png';
$heroStmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_name = :name LIMIT 1");
$heroStmt->execute([':name' => 'hero_image']);
$heroRow = $heroStmt->fetch(PDO::FETCH_ASSOC);

if ($heroRow && !empty($heroRow['setting_value'])) {
    $heroImage = $heroRow['setting_value'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hero_image'])) {
    $newHeroImage = trim($_POST['hero_image']);

    if ($newHeroImage === '') {
        $error = 'Vul een afbeelding pad of URL in.';
    } else {
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
</head>
<body>
    <h1>Welkom in het admin panel</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="hero_image">Homepage afbeelding:</label><br>
        <input
            type="text"
            id="hero_image"
            name="hero_image"
            value="<?= htmlspecialchars($heroImage) ?>"
            style="width: 420px; margin-top: 8px;"
        >
        <br><br>
        <button type="submit">Opslaan</button>
    </form>

    <h3>Preview:</h3>
    <img src="<?= htmlspecialchars($heroImage) ?>" alt="Homepage preview" style="max-width: 500px;">

    <form method="post" action="index.php" style="margin-top: 20px;">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
