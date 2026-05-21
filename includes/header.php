<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($pageTitle)) {
    $pageTitle = "Vite & Gourmand";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/index.php">
            <img src="https://viteetgourmand.com/assets/img/Logo_VG.png" alt="Logo" class="logo-navbar">
            <span>Vite & Gourmand</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pages/menus.php">Menus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pages/contact.php">Contact</a>
                </li>

                <?php if (isset($_SESSION['utilisateur'])): ?>
                    <?php if ($_SESSION['utilisateur']['role'] === 'administrateur'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard.php">Dashboard</a>
                        </li>
                    <?php elseif ($_SESSION['utilisateur']['role'] === 'employe'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/employee/dashboard.php">Dashboard</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/user/dashboard.php">Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/actions/logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/register.php">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/login.php">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>