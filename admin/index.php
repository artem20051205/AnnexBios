<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if ($gebruikersnaam === '' || $wachtwoord === '') {
        $error = 'Vul alle velden in.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM admin_log WHERE gebruikersnaam = :gebruikersnaam LIMIT 1');
        $stmt->execute([
            ':gebruikersnaam' => $gebruikersnaam,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && ($wachtwoord === $user['wachtwoord'] || password_verify($wachtwoord, $user['wachtwoord']))) {
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
</head>
<body>
    <h1>Admin Panel</h1>

    <?php if (!empty($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam">
        <input type="password" name="wachtwoord" placeholder="Wachtwoord">
        <button type="submit">Inloggen</button>
    </form>
</body>
</html>