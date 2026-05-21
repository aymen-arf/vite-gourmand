<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$pays = trim($_POST['pays'] ?? '');
$adresse_postale = trim($_POST['adresse_postale'] ?? '');

if (
    empty($nom) || empty($prenom) || empty($telephone) ||
    empty($ville) || empty($pays) || empty($adresse_postale)
) {
    header('Location: /vite-gourmand/user/profile.php?error=Tous les champs sont obligatoires');
    exit;
}

$stmt = $pdo->prepare("
    UPDATE utilisateur
    SET nom = ?, prenom = ?, telephone = ?, ville = ?, pays = ?, adresse_postale = ?
    WHERE utilisateur_id = ?
");

$stmt->execute([
    $nom,
    $prenom,
    $telephone,
    $ville,
    $pays,
    $adresse_postale,
    $_SESSION['utilisateur']['id']
]);

$_SESSION['utilisateur']['nom'] = $nom;
$_SESSION['utilisateur']['prenom'] = $prenom;

header('Location: /vite-gourmand/user/profile.php?success=1');
exit;