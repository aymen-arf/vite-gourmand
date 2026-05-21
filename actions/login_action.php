<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: /vite-gourmand/pages/login.php?error=Veuillez remplir tous les champs');
    exit;
}

$stmt = $pdo->prepare("
    SELECT u.*, r.libelle AS role_libelle
    FROM utilisateur u
    INNER JOIN role r ON u.role_id = r.role_id
    WHERE u.email = ? AND u.actif = 1
    LIMIT 1
");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: /vite-gourmand/pages/login.php?error=Identifiants invalides');
    exit;
}

$_SESSION['utilisateur'] = [
    'id' => $user['utilisateur_id'],
    'nom' => $user['nom'],
    'prenom' => $user['prenom'],
    'email' => $user['email'],
    'role' => $user['role_libelle']
];

if ($user['role_libelle'] === 'administrateur') {
    header('Location: /vite-gourmand/admin/dashboard.php');
    exit;
}

if ($user['role_libelle'] === 'employe') {
    header('Location: /vite-gourmand/employee/dashboard.php');
    exit;
}

header('Location: /vite-gourmand/user/dashboard.php');
exit;
if (!$utilisateur['actif']) {
    header('Location: /vite-gourmand/pages/login.php?error=Compte désactivé');
    exit;
}