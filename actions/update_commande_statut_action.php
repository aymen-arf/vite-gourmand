<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/employee/dashboard.php');
    exit;
}

$commandeId = (int)($_POST['commande_id'] ?? 0);
$statut = trim($_POST['statut'] ?? '');

$allowed = [
    'en attente',
    'accepté',
    'refusée',
    'en préparation',
    'en cours de livraison',
    'livré',
    'en attente du retour de matériel',
    'terminée'
];

if ($commandeId > 0 && in_array($statut, $allowed, true)) {
    $stmt = $pdo->prepare("UPDATE commande SET statut = ? WHERE commande_id = ?");
    $stmt->execute([$statut, $commandeId]);

    $suivi = $pdo->prepare("INSERT INTO suivi_commande (commande_id, statut) VALUES (?, ?)");
    $suivi->execute([$commandeId, $statut]);

    header('Location: /vite-gourmand/employee/dashboard.php?success=1');
    exit;
}

header('Location: /vite-gourmand/employee/dashboard.php?error=' . urlencode('Statut invalide'));
exit;