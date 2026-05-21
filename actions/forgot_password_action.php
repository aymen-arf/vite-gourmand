<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/forgot_password.php');
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /pages/forgot_password.php?error=' . urlencode('Adresse e-mail invalide'));
    exit;
}

$stmt = $pdo->prepare("SELECT utilisateur_id, prenom, email FROM utilisateur WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', time() + 1200); // 20 minutes

    $update = $pdo->prepare("
        UPDATE utilisateur
        SET reset_token = ?, reset_token_expires_at = ?
        WHERE utilisateur_id = ?
    ");
    $update->execute([$token, $expiresAt, $user['utilisateur_id']]);

    $resetLink = "http://localhost/pages/reset_password.php?token=" . urlencode($token);

    $sujet = "Réinitialisation de votre mot de passe";
    $message = "Bonjour " . $user['prenom'] . ",\n\n"
        . "Vous avez demandé la réinitialisation de votre mot de passe.\n"
        . "Cliquez sur le lien suivant pour choisir un nouveau mot de passe :\n\n"
        . $resetLink . "\n\n"
        . "Ce lien est valide pendant 20 minutes.\n\n"
        . "Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.\n\n"
        . "Vite & Gourmand";
    $headers = "From: no-reply@vitegourmand.fr\r\n";

    @mail($user['email'], $sujet, $message, $headers);
}

header('Location: /pages/forgot_password.php?success=1');
exit;