<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /pages/login.php');
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO menu (titre, nombre_personne_minimum, prix_par_personne, regime_id, theme_id, description, conditions_menu, stock_disponible)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $_POST['titre'],
    $_POST['nombre_personne_minimum'],
    $_POST['prix_par_personne'],
    $_POST['regime_id'],
    $_POST['theme_id'],
    $_POST['description'],
    $_POST['conditions_menu'],
    $_POST['stock_disponible']
]);

header('Location: /employee/dashboard.php?success=1');
exit;