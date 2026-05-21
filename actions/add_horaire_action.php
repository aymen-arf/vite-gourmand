<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /pages/login.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES (?, ?, ?)");
$stmt->execute([
    $_POST['jour'],
    $_POST['heure_ouverture'],
    $_POST['heure_fermeture']
]);

header('Location: /employee/dashboard.php?success=1');
exit;