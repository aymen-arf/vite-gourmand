<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /pages/login.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO plat (titre_plat, type_plat, prix) VALUES (?, ?, ?)");
$stmt->execute([
    $_POST['titre_plat'],
    $_POST['type_plat'],
    $_POST['prix']
]);

header('Location: /employee/dashboard.php?success=1');
exit;