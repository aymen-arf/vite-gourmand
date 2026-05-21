<?php
session_start();
require_once '../config/database.php';
require_once '../config/mongodb.php';
require_once '../config/mailer.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}

$menuId = (int)($_POST['menu_id'] ?? 0);
$adressePrestation = trim($_POST['adresse_prestation'] ?? '');
$lieuPrestation = trim($_POST['lieu_prestation'] ?? '');
$datePrestation = $_POST['date_prestation'] ?? '';
$heureLivraison = $_POST['heure_livraison'] ?? '';
$nombrePersonne = (int)($_POST['nombre_personne'] ?? 0);
$distanceKm = (float)($_POST['distance_km'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menuId]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    header('Location: /vite-gourmand/pages/menus.php');
    exit;
}

$minimum = (int)$menu['nombre_personne_minimum'];
$prixParPersonne = (float)$menu['prix_par_personne'];

if (
    empty($adressePrestation) ||
    empty($lieuPrestation) ||
    empty($datePrestation) ||
    empty($heureLivraison) ||
    $nombrePersonne < $minimum
) {
    header('Location: /vite-gourmand/pages/commande.php?menu_id=' . $menuId . '&error=' . urlencode('Veuillez respecter le minimum de personnes et remplir tous les champs'));
    exit;
}

if ($distanceKm < 0) {
    $distanceKm = 0;
}

$prixMenu = $prixParPersonne * $nombrePersonne;
$remise = 0;

if ($nombrePersonne >= ($minimum + 5)) {
    $remise = $prixMenu * 0.10;
}

$prixMenuFinal = $prixMenu - $remise;

$prixLivraison = 0;
if (stripos($adressePrestation, 'bordeaux') === false && stripos($lieuPrestation, 'bordeaux') === false) {
    $prixLivraison = 5.00 + (0.59 * $distanceKm);
}

$prixTotal = $prixMenuFinal + $prixLivraison;
$numeroCommande = 'CMD-' . time();
$dateCommande = date('Y-m-d');

$insert = $pdo->prepare("
    INSERT INTO commande (
        numero_commande,
        date_commande,
        date_prestation,
        heure_livraison,
        lieu_prestation,
        adresse_prestation,
        prix_menu,
        prix_livraison,
        prix_total,
        nombre_personne,
        statut,
        pret_materiel,
        restitution_materiel,
        utilisateur_id,
        menu_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$insert->execute([
    $numeroCommande,
    $dateCommande,
    $datePrestation,
    $heureLivraison,
    $lieuPrestation,
    $adressePrestation,
    $prixMenuFinal,
    $prixLivraison,
    $prixTotal,
    $nombrePersonne,
    'en attente',
    0,
    0,
    $_SESSION['utilisateur']['id'],
    $menuId
]);

$commandeId = $pdo->lastInsertId();

$suivi = $pdo->prepare("INSERT INTO suivi_commande (commande_id, statut) VALUES (?, ?)");
$suivi->execute([$commandeId, 'en attente']);

if ($mongoStats) {
    $mongoStats->insertOne([
        'commande_id_sql' => (int)$commandeId,
        'menu_id' => (int)$menu['menu_id'],
        'menu' => $menu['titre'],
        'utilisateur_id' => (int)$_SESSION['utilisateur']['id'],
        'prix_total' => (float)$prixTotal,
        'statut' => 'en attente',
        'date_commande' => new MongoDB\BSON\UTCDateTime()
    ]);
}

$destinataire = $_SESSION['utilisateur']['email'] ?? '';
$nomClient = trim(($_SESSION['utilisateur']['prenom'] ?? '') . ' ' . ($_SESSION['utilisateur']['nom'] ?? ''));

if (!empty($destinataire)) {
    $sujet = 'Confirmation de votre commande - Vite & Gourmand';

    $html = "
        <h2>Confirmation de commande</h2>
        <p>Bonjour " . htmlspecialchars($nomClient) . ",</p>
        <p>Votre commande <strong>" . htmlspecialchars($numeroCommande) . "</strong> a bien été enregistrée.</p>
        <p><strong>Menu :</strong> " . htmlspecialchars($menu['titre']) . "</p>
        <p><strong>Date de prestation :</strong> " . htmlspecialchars($datePrestation) . "</p>
        <p><strong>Heure de livraison :</strong> " . htmlspecialchars($heureLivraison) . "</p>
        <p><strong>Nombre de personnes :</strong> " . (int)$nombrePersonne . "</p>
        <p><strong>Prix menu :</strong> " . number_format($prixMenuFinal, 2, ',', ' ') . " €</p>
        <p><strong>Prix livraison :</strong> " . number_format($prixLivraison, 2, ',', ' ') . " €</p>
        <p><strong>Total :</strong> " . number_format($prixTotal, 2, ',', ' ') . " €</p>
        <p><strong>Statut :</strong> en attente</p>
    ";

    $texte = "Bonjour $nomClient,\n"
        . "Votre commande $numeroCommande a bien ete enregistree.\n"
        . "Menu : {$menu['titre']}\n"
        . "Date : $datePrestation\n"
        . "Heure : $heureLivraison\n"
        . "Nombre de personnes : $nombrePersonne\n"
        . "Prix menu : " . number_format($prixMenuFinal, 2, ',', ' ') . " EUR\n"
        . "Prix livraison : " . number_format($prixLivraison, 2, ',', ' ') . " EUR\n"
        . "Total : " . number_format($prixTotal, 2, ',', ' ') . " EUR\n"
        . "Statut : en attente";

    envoyerMail($destinataire, $nomClient, $sujet, $html, $texte);
}

header('Location: /vite-gourmand/user/dashboard.php?success=commande');
exit;