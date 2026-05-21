<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$commandeId = (int)($_POST['commande_id'] ?? 0);
$adressePrestation = trim($_POST['adresse_prestation'] ?? '');
$lieuPrestation = trim($_POST['lieu_prestation'] ?? '');
$datePrestation = $_POST['date_prestation'] ?? '';
$heureLivraison = $_POST['heure_livraison'] ?? '';
$nombrePersonne = (int)($_POST['nombre_personne'] ?? 0);

$stmt = $pdo->prepare("
    SELECT c.*, m.nombre_personne_minimum, m.prix_par_personne
    FROM commande c
    INNER JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.commande_id = ? AND c.utilisateur_id = ?
");
$stmt->execute([$commandeId, $_SESSION['utilisateur']['id']]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande || $commande['statut'] === 'accepté') {
    header('Location: /vite-gourmand/user/dashboard.php');
    exit;
}

$minimum = (int)$commande['nombre_personne_minimum'];
$prixParPersonne = (float)$commande['prix_par_personne'];

if ($nombrePersonne < $minimum) {
    header('Location: /vite-gourmand/user/commande-detail.php?id=' . $commandeId . '&edit=1');
    exit;
}

$prixMenu = $prixParPersonne * $nombrePersonne;
$remise = 0;

if ($nombrePersonne >= ($minimum + 5)) {
    $remise = $prixMenu * 0.10;
}

$prixMenuFinal = $prixMenu - $remise;

$prixLivraison = 0;
if (stripos($adressePrestation, 'bordeaux') === false && stripos($lieuPrestation, 'bordeaux') === false) {
    $prixLivraison = 5.00;
}

$prixTotal = $prixMenuFinal + $prixLivraison;

$update = $pdo->prepare("
    UPDATE commande
    SET adresse_prestation = ?, lieu_prestation = ?, date_prestation = ?, heure_livraison = ?,
        nombre_personne = ?, prix_menu = ?, prix_livraison = ?, prix_total = ?
    WHERE commande_id = ?
");
$update->execute([
    $adressePrestation,
    $lieuPrestation,
    $datePrestation,
    $heureLivraison,
    $nombrePersonne,
    $prixMenuFinal,
    $prixLivraison,
    $prixTotal,
    $commandeId
]);

header('Location: /vite-gourmand/user/dashboard.php?success=1');
exit;