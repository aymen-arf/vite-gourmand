<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once '../config/database.php';

$commandeId = (int)($_GET['id'] ?? 0);
$editMode = isset($_GET['edit']) && $_GET['edit'] == 1;

$stmt = $pdo->prepare("
    SELECT c.*, m.titre, m.nombre_personne_minimum, m.prix_par_personne
    FROM commande c
    INNER JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.commande_id = ? AND c.utilisateur_id = ?
");
$stmt->execute([$commandeId, $_SESSION['utilisateur']['id']]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: /user/dashboard.php');
    exit;
}

$pageTitle = "Détail commande - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Détail de la commande</h1>

    <div class="card shadow-sm p-4 mb-4">
        <p><strong>Numéro :</strong> <?= htmlspecialchars($commande['numero_commande']) ?></p>
        <p><strong>Menu :</strong> <?= htmlspecialchars($commande['titre']) ?></p>
        <p><strong>Date prestation :</strong> <?= htmlspecialchars($commande['date_prestation']) ?></p>
        <p><strong>Heure livraison :</strong> <?= htmlspecialchars($commande['heure_livraison']) ?></p>
        <p><strong>Lieu :</strong> <?= htmlspecialchars($commande['lieu_prestation']) ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($commande['adresse_prestation']) ?></p>
        <p><strong>Personnes :</strong> <?= htmlspecialchars($commande['nombre_personne']) ?></p>
        <p><strong>Prix menu :</strong> <?= number_format($commande['prix_menu'], 2, ',', ' ') ?> €</p>
        <p><strong>Prix livraison :</strong> <?= number_format($commande['prix_livraison'], 2, ',', ' ') ?> €</p>
        <p><strong>Total :</strong> <?= number_format($commande['prix_total'], 2, ',', ' ') ?> €</p>
        <p><strong>Statut :</strong> <?= htmlspecialchars($commande['statut']) ?></p>
    </div>

    <?php if ($editMode && $commande['statut'] !== 'accepté'): ?>
        <div class="card shadow-sm p-4 mb-4">
            <h2 class="h4 mb-3">Modifier la commande</h2>

            <form action="/actions/update_commande_action.php" method="post">
                <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Adresse prestation</label>
                    <input type="text" name="adresse_prestation" class="form-control" value="<?= htmlspecialchars($commande['adresse_prestation']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lieu prestation</label>
                    <input type="text" name="lieu_prestation" class="form-control" value="<?= htmlspecialchars($commande['lieu_prestation']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date prestation</label>
                        <input type="date" name="date_prestation" class="form-control" value="<?= htmlspecialchars($commande['date_prestation']) ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Heure livraison</label>
                        <input type="time" name="heure_livraison" class="form-control" value="<?= htmlspecialchars($commande['heure_livraison']) ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre de personnes</label>
                        <input type="number" name="nombre_personne" class="form-control" min="<?= (int)$commande['nombre_personne_minimum'] ?>" value="<?= htmlspecialchars($commande['nombre_personne']) ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($commande['statut'] === 'terminée'): ?>
        <div class="card shadow-sm p-4" id="avis">
            <h2 class="h4 mb-3">Donner un avis</h2>

            <form action="/actions/avis_action.php" method="post">
                <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <select name="note" class="form-select" required>
                        <option value="">Choisir une note</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Commentaire</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">Envoyer mon avis</button>
            </form>
        </div>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>