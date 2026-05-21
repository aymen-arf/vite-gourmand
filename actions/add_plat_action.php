<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO plat (titre_plat, type_plat, prix) VALUES (?, ?, ?)");
$stmt->execute([
    $_POST['titre_plat'],
    $_POST['type_plat'],
    $_POST['prix']
]);

header('Location: /vite-gourmand/employee/dashboard.php?success=1');
exit;