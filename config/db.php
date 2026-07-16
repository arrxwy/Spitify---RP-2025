<?php
require_once 'config.php';

try {
    $pdo = new PDO(
        "mysql:host=$server;dbname=$database;charset=utf8mb4", 
        $username, 
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Připojení k databázi selhalo: " . $e->getMessage());
}
?>