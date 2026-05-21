<?php
$pageTitle = "Mot de passe oublié - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h1 class="section-title">Mot de passe oublié</h1>

            <div class="alert alert-info">
                Saisissez votre adresse e-mail. Si un compte existe, un lien de réinitialisation sera envoyé.
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Si un compte existe, un lien de réinitialisation a été envoyé.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/forgot_password_action.php" method="post" class="card shadow-sm p-4">
                <div class="mb-3">
                    <label class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>