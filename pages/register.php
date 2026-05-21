<?php
$pageTitle = "Inscription - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="section-title">Créer un compte</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Votre compte a été créé avec succès.</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="/vite-gourmand/actions/register_action.php" method="post" class="card shadow-sm p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input
                    type="tel"
                    name="telephone"
                    class="form-control"
                    pattern="[0-9]{10}"
                    maxlength="10"
                    inputmode="numeric"
                    placeholder="0612345678"
                    title="Le numéro doit contenir exactement 10 chiffres."
                    required
                >
                 </div>

                <div class="mb-3">
                    <label class="form-label">Ville</label>
                    <input type="text" name="ville" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pays</label>
                    <input type="text" name="pays" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Code postal</label>
                    <input
                        type="text"
                        name="code_postal"
                        class="form-control"
                        pattern="[0-9]{5}"
                        maxlength="5"
                        inputmode="numeric"
                        placeholder="33000"
                        title="Le code postal doit contenir exactement 5 chiffres."
                        required
                           >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse postale</label>
                        <input
                            type="text"
                            name="adresse_postale"
                            class="form-control"
                            placeholder="10 rue des Fleurs"
                            required
                        >
                    </div>

                <div class="mb-3">
                    <label class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                    <div class="form-text">
                        10 caractères minimum, avec majuscule, minuscule, chiffre et caractère spécial.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Créer mon compte</button>
            </form>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>