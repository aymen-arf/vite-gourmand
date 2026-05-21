<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur') {
    header('Location: /pages/login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$actif = (int)($_GET['actif'] ?? 0);

if ($id > 0 && in_array($actif, [0, 1], true)) {
    $stmt = $pdo->prepare("
        UPDATE utilisateur u
        INNER JOIN role r ON u.role_id = r.role_id
        SET u.actif = ?
        WHERE u.utilisateur_id = ? AND r.libelle = 'employe'
    ");
    $stmt->execute([$actif, $id]);
}

header('Location: /admin/dashboard.php?success=1');
exit;