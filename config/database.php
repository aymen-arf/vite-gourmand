<?php
$host = 'mysql-arfaoui.alwaysdata.net';
$dbname = 'arfaoui_vite_gourmand';
$username = 'arfaoui';
$password = 'Aymen69008@';
$port = 3306; 

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

