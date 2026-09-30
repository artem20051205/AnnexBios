<?php

// database in docker (lokaal)
$host = 'db';
$dbname = 'annexbios';
$username = 'root';
$password = 'root';

// op de server staat db-config.php met de gegevens van de hosting
// op localhost slaan we dat bestand over, anders werkt docker niet meer
$isLokaal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true);
if (!$isLokaal && file_exists(__DIR__ . '/db-config.php')) {
    require __DIR__ . '/db-config.php';
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // bij een fout gooit pdo een exception, die vangen we onderaan op
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // settings tabel aanmaken als die nog niet bestaat
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            setting_name VARCHAR(100) PRIMARY KEY,
            setting_value TEXT NOT NULL
        )
    ");

    // nog geen hero afbeelding? dan dune.png als standaard opslaan
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_name = :name");
    $stmt->execute([':name' => 'hero_image']);

    if ((int) $stmt->fetchColumn() === 0) {
        $insert = $pdo->prepare("INSERT INTO site_settings (setting_name, setting_value) VALUES (:name, :value)");
        $insert->execute([
            ':name' => 'hero_image',
            ':value' => 'style/images/dune.png'
        ]);
    }
} catch (PDOException $e) {
    die("error: " . $e->getMessage());
}