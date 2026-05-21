<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$pays = trim($_POST['pays'] ?? '');
$adresse_postale = trim($_POST['adresse_postale'] ?? '');
$password = $_POST['password'] ?? '';

if (
    empty($nom) || empty($prenom) || empty($email) || empty($telephone) ||
    empty($ville) || empty($pays) || empty($adresse_postale) || empty($password)
) {
    header('Location: /vite-gourmand/admin/dashboard.php');
    exit;
}

$roleStmt = $pdo->prepare("SELECT role_id FROM role WHERE libelle = 'employe' LIMIT 1");
$roleStmt->execute();
$roleEmploye = $roleStmt->fetch(PDO::FETCH_ASSOC);

if (!$roleEmploye) {
    header('Location: /vite-gourmand/admin/dashboard.php');
    exit;
}

$check = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
$check->execute([$email]);

if (!$check->fetch()) {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO utilisateur (nom, prenom, telephone, ville, pays, adresse_postale, email, password, actif, role_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)
    ");

    $stmt->execute([
        $nom,
        $prenom,
        $telephone,
        $ville,
        $pays,
        $adresse_postale,
        $email,
        $hash,
        $roleEmploye['role_id']
    ]);
}

header('Location: /vite-gourmand/admin/dashboard.php?success=1');
exit;