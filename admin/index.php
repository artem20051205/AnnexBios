<?php
require_once '../db.php';
// sessie nodig om te onthouden dat je bent ingelogd
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if ($gebruikersnaam === '' || $wachtwoord === '') {
        $error = 'Vul alle velden in.';
    } else {
        // admin zoeken op gebruikersnaam
        $stmt = $pdo->prepare('SELECT * FROM admin_log WHERE gebruikersnaam = :gebruikersnaam LIMIT 1');
        $stmt->execute([
            ':gebruikersnaam' => $gebruikersnaam,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // wachtwoord kan gewoon of als hash in de database staan
        if ($user && ($wachtwoord === $user['wachtwoord'] || password_verify($wachtwoord, $user['wachtwoord']))) {
            // hier kijken de andere admin pagina's naar
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];

            header('Location: admin.php');
            exit;
        }

        $error = 'Gebruikersnaam of wachtwoord is onjuist.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" type="image/png" href="../style/images/favicon.png">
    <link rel="stylesheet" href="../style/output.css?v=<?= filemtime(__DIR__ . '/../style/output.css') ?>">
    <link rel="stylesheet" href="../style/style.css?v=<?= filemtime(__DIR__ . '/../style/style.css') ?>">
</head>
<body>
    <?php $base = '../'; include '../includes/header.php'; ?>

    <main class="chairs">
        <h1>Admin Panel</h1>

        <?php if (!empty($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="customer">
                <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam">
                <input type="password" name="wachtwoord" placeholder="Wachtwoord">
            </div>
            <button type="submit" class="confirm">Inloggen</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>