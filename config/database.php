<?php
$host = '127.0.0.1';
$dbname = 'vite_gourmand';
$username = 'root';
$password = '';
$port = 3307; // Port MySQL utilisé car 3306 était déjà occupé sur ma machine

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

