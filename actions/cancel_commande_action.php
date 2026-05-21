<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$commandeId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT * FROM commande
    WHERE commande_id = ? AND utilisateur_id = ?
");
$stmt->execute([$commandeId, $_SESSION['utilisateur']['id']]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande || $commande['statut'] === 'accepté') {
    header('Location: /vite-gourmand/user/dashboard.php');
    exit;
}

$deleteSuivi = $pdo->prepare("DELETE FROM suivi_commande WHERE commande_id = ?");
$deleteSuivi->execute([$commandeId]);

$deleteCommande = $pdo->prepare("DELETE FROM commande WHERE commande_id = ?");
$deleteCommande->execute([$commandeId]);

header('Location: /vite-gourmand/user/dashboard.php?success=1');
exit;