<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/forgot_password.php');
    exit;
}

$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

if (empty($token) || empty($password) || empty($passwordConfirm)) {
    header('Location: /pages/forgot_password.php?error=' . urlencode('Requête invalide'));
    exit;
}

if ($password !== $passwordConfirm) {
    header('Location: /pages/reset_password.php?token=' . urlencode($token) . '&error=' . urlencode('Les mots de passe ne correspondent pas'));
    exit;
}

if (
    strlen($password) < 10 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[\W_]/', $password)
) {
    header('Location: /pages/reset_password.php?token=' . urlencode($token) . '&error=' . urlencode('Mot de passe non conforme'));
    exit;
}

$stmt = $pdo->prepare("
    SELECT utilisateur_id, reset_token_expires_at
    FROM utilisateur
    WHERE reset_token = ?
    LIMIT 1
");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /pages/forgot_password.php?error=' . urlencode('Lien invalide ou expiré'));
    exit;
}

if (strtotime($user['reset_token_expires_at']) < time()) {
    header('Location: /pages/forgot_password.php?error=' . urlencode('Lien invalide ou expiré'));
    exit;
}

$newHashedPassword = password_hash($password, PASSWORD_DEFAULT);

$update = $pdo->prepare("
    UPDATE utilisateur
    SET password = ?, reset_token = NULL, reset_token_expires_at = NULL
    WHERE utilisateur_id = ?
");
$update->execute([$newHashedPassword, $user['utilisateur_id']]);

header('Location: /pages/login.php?success=' . urlencode('Mot de passe réinitialisé avec succès'));
exit;