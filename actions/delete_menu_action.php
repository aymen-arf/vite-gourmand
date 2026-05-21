<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /pages/login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM menu WHERE menu_id = ?");
    $stmt->execute([$id]);
}

header('Location: /employee/dashboard.php?success=1');
exit;