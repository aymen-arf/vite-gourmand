<?php
$pageTitle = "Connexion - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="section-title">Connexion</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="/actions/login_action.php" method="post" class="card shadow-sm p-4">
                <div class="mb-3">
                    <label class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mb-3">Se connecter</button>
                <a href="/pages/forgot_password.php">Mot de passe oublié ?</a>
            </form>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>