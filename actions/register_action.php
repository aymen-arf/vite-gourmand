<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/register.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$code_postal = trim($_POST['code_postal'] ?? '');
$pays = trim($_POST['pays'] ?? '');
$adresse_postale = trim($_POST['adresse_postale'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (
    empty($nom) || empty($prenom) || empty($telephone) || empty($ville) ||
    empty($code_postal) || empty($pays) || empty($adresse_postale) ||
    empty($email) || empty($password)
) {
    header('Location: /pages/register.php?error=' . urlencode('Tous les champs sont obligatoires'));
    exit;
}

if (!preg_match('/^[0-9]{10}$/', $telephone)) {
    header('Location: /pages/register.php?error=' . urlencode('Le numéro de téléphone doit contenir exactement 10 chiffres'));
    exit;
}

if (!preg_match('/^[0-9]{5}$/', $code_postal)) {
    header('Location: /pages/register.php?error=' . urlencode('Le code postal doit contenir exactement 5 chiffres'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /pages/register.php?error=' . urlencode('Adresse e-mail invalide'));
    exit;
}

if (
    strlen($password) < 10 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[\W_]/', $password)
) {
    header('Location: /pages/register.php?error=' . urlencode('Mot de passe non conforme'));
    exit;
}

$stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    header('Location: /pages/register.php?error=' . urlencode('Cette adresse e-mail existe déjà'));
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$roleStmt = $pdo->prepare("SELECT role_id FROM role WHERE libelle = 'utilisateur' LIMIT 1");
$roleStmt->execute();
$role = $roleStmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    header('Location: /pages/register.php?error=' . urlencode('Rôle utilisateur introuvable'));
    exit;
}

try {
    $insert = $pdo->prepare("
        INSERT INTO utilisateur (nom, prenom, telephone, ville, code_postal, pays, adresse_postale, email, password, role_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $nom,
        $prenom,
        $telephone,
        $ville,
        $code_postal,
        $pays,
        $adresse_postale,
        $email,
        $hashedPassword,
        $role['role_id']
    ]);

    $sujet = "Bienvenue sur Vite & Gourmand";
    $message = "Bonjour $prenom,\n\n"
        . "Votre compte a bien été créé sur Vite & Gourmand.\n"
        . "Vous pouvez désormais vous connecter à votre espace.\n\n"
        . "À bientôt,\n"
        . "L'équipe Vite & Gourmand";
    $headers = "From: no-reply@vitegourmand.fr\r\n";

    @mail($email, $sujet, $message, $headers);

    header('Location: /pages/register.php?success=1');
    exit;
} catch (PDOException $e) {
    header('Location: /pages/register.php?error=' . urlencode('Erreur lors de la création du compte'));
    exit;
}