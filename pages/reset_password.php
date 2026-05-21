<?php
$pageTitle = "Réinitialiser le mot de passe - Vite & Gourmand";
include '../includes/header.php';

$token = $_GET['token'] ?? '';
?>

<section class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="section-title">Réinitialiser le mot de passe</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="/actions/reset_password_action.php" method="post" class="card shadow-sm p-4">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirm" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Réinitialiser</button>
            </form>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>