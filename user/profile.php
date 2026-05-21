<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

require_once '../config/database.php';

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$_SESSION['utilisateur']['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = "Mon profil - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Mon profil</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Profil mis à jour avec succès.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="/vite-gourmand/actions/update_profile_action.php" method="post" class="card shadow-sm p-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($user['ville']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pays</label>
            <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($user['pays']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Adresse postale</label>
            <input type="text" name="adresse_postale" class="form-control" value="<?= htmlspecialchars($user['adresse_postale']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
    </form>
</section>

<?php include '../includes/footer.php'; ?>