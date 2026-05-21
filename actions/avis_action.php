<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$commandeId = (int)($_POST['commande_id'] ?? 0);
$note = (int)($_POST['note'] ?? 0);
$description = trim($_POST['description'] ?? '');

$stmt = $pdo->prepare("
    SELECT * FROM commande
    WHERE commande_id = ? AND utilisateur_id = ? AND statut = 'terminée'
");
$stmt->execute([$commandeId, $_SESSION['utilisateur']['id']]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande || $note < 1 || $note > 5 || empty($description)) {
    header('Location: /vite-gourmand/user/dashboard.php');
    exit;
}

$checkAvis = $pdo->prepare("SELECT avis_id FROM avis WHERE commande_id = ?");
$checkAvis->execute([$commandeId]);

if (!$checkAvis->fetch()) {
    $insert = $pdo->prepare("
        INSERT INTO avis (note, description, statut, utilisateur_id, commande_id)
        VALUES (?, ?, 'en attente', ?, ?)
    ");
    $insert->execute([$note, $description, $_SESSION['utilisateur']['id'], $commandeId]);
}

header('Location: /vite-gourmand/user/dashboard.php?success=1');
exit;