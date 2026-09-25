<?php

$host = 'db';
$dbname = 'annexbios';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            setting_name VARCHAR(100) PRIMARY KEY,
            setting_value TEXT NOT NULL
        )
    ");

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