<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /pages/login.php');
    exit;
}

$avisId = (int)($_GET['id'] ?? 0);
$statut = trim($_GET['statut'] ?? '');

if ($avisId > 0 && in_array($statut, ['valide', 'refuse'], true)) {
    $stmt = $pdo->prepare("UPDATE avis SET statut = ? WHERE avis_id = ?");
    $stmt->execute([$statut, $avisId]);
}

header('Location: /employee/dashboard.php?success=1');
exit;