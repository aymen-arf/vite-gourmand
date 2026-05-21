<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /vite-gourmand/pages/login.php');
    exit;
}

require_once '../config/database.php';

$pageTitle = "Commander un menu - Vite & Gourmand";
include '../includes/header.php';

$menuId = $_GET['menu_id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menuId]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    echo "<div class='container'><div class='alert alert-danger'>Menu introuvable.</div></div>";
    include '../includes/footer.php';
    exit;
}

$minimum = (int)$menu['nombre_personne_minimum'];
$prixParPersonne = (float)$menu['prix_par_personne'];
?>

<section class="container">
    <h1 class="section-title">Commande d’un menu</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="/vite-gourmand/actions/commande_action.php" method="post" class="card shadow-sm p-4" id="commandeForm">
                <input type="hidden" name="menu_id" value="<?= htmlspecialchars($menu['menu_id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Menu sélectionné</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($menu['titre']) ?>" readonly>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['utilisateur']['nom']) ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['utilisateur']['prenom']) ?>" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['utilisateur']['email']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse de la prestation</label>
                    <input type="text" name="adresse_prestation" id="adresse_prestation" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lieu de la prestation</label>
                    <input type="text" name="lieu_prestation" id="lieu_prestation" class="form-control" required>
                    <div class="form-text">Indiquez Bordeaux si la prestation a lieu à Bordeaux.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Distance en kilomètres (si hors Bordeaux)</label>
                    <input type="number" name="distance_km" id="distance_km" class="form-control" min="0" step="0.1" value="0">
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date de prestation</label>
                        <input type="date" name="date_prestation" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Heure de livraison</label>
                        <input type="time" name="heure_livraison" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre de personnes</label>
                        <input
                            type="number"
                            name="nombre_personne"
                            id="nombre_personne"
                            class="form-control"
                            min="<?= $minimum ?>"
                            value="<?= $minimum ?>"
                            required
                        >
                    </div>
                </div>

                <div class="alert alert-warning">
                    Minimum de commande : <strong><?= $minimum ?></strong> personnes.<br>
                    Remise de 10 % appliquée à partir de <strong><?= $minimum + 5 ?></strong> personnes.
                </div>

                <button type="submit" class="btn btn-success">Valider la commande</button>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm p-4">
                <h3 class="h5">Résumé du menu</h3>
                <p><strong>Prix par personne :</strong> <?= number_format($prixParPersonne, 2, ',', ' ') ?> €</p>
                <p><strong>Nombre minimum :</strong> <?= $minimum ?></p>
                <p><strong>Stock disponible :</strong> <?= htmlspecialchars($menu['stock_disponible']) ?></p>
                <p><strong>Conditions :</strong><br><?= nl2br(htmlspecialchars($menu['conditions_menu'])) ?></p>
                <hr>
                <h4 class="h6">Détail du prix</h4>
                <p><strong>Sous-total :</strong> <span id="resume_prix_menu"><?= number_format($prixParPersonne * $minimum, 2, ',', ' ') ?> €</span></p>
                <p><strong>Remise :</strong> <span id="resume_remise">0,00 €</span></p>
                <p><strong>Livraison :</strong> <span id="resume_livraison">0,00 €</span></p>
                <p class="mb-0"><strong>Total :</strong> <span id="resume_total"><?= number_format($prixParPersonne * $minimum, 2, ',', ' ') ?> €</span></p>
            </div>
        </div>
    </div>
</section>

<script>
const prixParPersonne = <?= json_encode($prixParPersonne) ?>;
const minimum = <?= json_encode($minimum) ?>;

const nombreInput = document.getElementById('nombre_personne');
const lieuInput = document.getElementById('lieu_prestation');
const adresseInput = document.getElementById('adresse_prestation');
const distanceInput = document.getElementById('distance_km');

const resumePrixMenu = document.getElementById('resume_prix_menu');
const resumeRemise = document.getElementById('resume_remise');
const resumeLivraison = document.getElementById('resume_livraison');
const resumeTotal = document.getElementById('resume_total');

function formatEuro(value) {
    return value.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
}

function calculerResume() {
    let nombre = parseInt(nombreInput.value || minimum, 10);
    if (isNaN(nombre) || nombre < minimum) {
        nombre = minimum;
    }

    const lieu = (lieuInput.value || '').toLowerCase();
    const adresse = (adresseInput.value || '').toLowerCase();
    let distance = parseFloat(distanceInput.value || 0);
    if (isNaN(distance) || distance < 0) {
        distance = 0;
    }

    let sousTotal = prixParPersonne * nombre;
    let remise = 0;

    if (nombre >= minimum + 5) {
        remise = sousTotal * 0.10;
    }

    let livraison = 0;
    const estBordeaux = lieu.includes('bordeaux') || adresse.includes('bordeaux');

    if (!estBordeaux) {
        livraison = 5 + (0.59 * distance);
    }

    let total = sousTotal - remise + livraison;

    resumePrixMenu.textContent = formatEuro(sousTotal);
    resumeRemise.textContent = formatEuro(remise);
    resumeLivraison.textContent = formatEuro(livraison);
    resumeTotal.textContent = formatEuro(total);
}

nombreInput.addEventListener('input', calculerResume);
lieuInput.addEventListener('input', calculerResume);
adresseInput.addEventListener('input', calculerResume);
distanceInput.addEventListener('input', calculerResume);

calculerResume();
</script>

<?php include '../includes/footer.php'; ?>