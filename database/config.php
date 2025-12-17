<?php

// $dsn = 'mysql:host=192.168.1.30;dbname=support_system';
$dsn = 'mysql:host=localhost;port=3306;dbname=support_system;charset=utf8mb4';
$usernameDB = 'root';
$passwordDB = '';

try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Something went wrong. Please try again later. " . $e->getMessage());
}
