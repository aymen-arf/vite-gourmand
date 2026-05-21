<?php
session_start();

if (!isset($_SESSION['utilisateur']) || !in_array($_SESSION['utilisateur']['role'], ['employe', 'administrateur'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once '../config/database.php';

$pageTitle = "Espace employé - Vite & Gourmand";

$client = trim($_GET['client'] ?? '');
$statut = trim($_GET['statut'] ?? '');

$sql = "
    SELECT c.*, m.titre, u.nom, u.prenom, u.email
    FROM commande c
    INNER JOIN menu m ON c.menu_id = m.menu_id
    INNER JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
    WHERE 1 = 1
";

$params = [];

if (!empty($client)) {
    $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR u.email LIKE ?)";
    $term = "%" . $client . "%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

if (!empty($statut)) {
    $sql .= " AND c.statut = ?";
    $params[] = $statut;
}

$sql .= " ORDER BY c.commande_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$menus = $pdo->query("SELECT * FROM menu ORDER BY menu_id DESC")->fetchAll(PDO::FETCH_ASSOC);
$plats = $pdo->query("SELECT * FROM plat ORDER BY plat_id DESC")->fetchAll(PDO::FETCH_ASSOC);
$horaires = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id ASC")->fetchAll(PDO::FETCH_ASSOC);
$avis = $pdo->query("
    SELECT a.*, u.nom, u.prenom, c.numero_commande
    FROM avis a
    INNER JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
    INNER JOIN commande c ON a.commande_id = c.commande_id
    WHERE a.statut = 'en attente'
    ORDER BY a.avis_id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$ca = null;

if (isset($_SESSION['utilisateur']['role']) && $_SESSION['utilisateur']['role'] === 'administrateur') {
    $caStmt = $pdo->query("
        SELECT
            COALESCE(SUM(prix_total), 0) AS chiffre_affaires_total,
            COALESCE(SUM(CASE WHEN statut IN ('livré', 'terminée') THEN prix_total ELSE 0 END), 0) AS chiffre_affaires_realise
        FROM commande
    ");
    $ca = $caStmt->fetch(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Espace employé</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Opération réalisée avec succès.</div>
    <?php endif; ?>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Filtrer les commandes</h2>
        <form method="get" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Client</label>
                <input type="text" name="client" class="form-control" value="<?= htmlspecialchars($client) ?>" placeholder="Nom, prénom ou email">
            </div>
            <div class="col-md-4">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="en attente" <?= $statut === 'en attente' ? 'selected' : '' ?>>en attente</option>
                    <option value="accepté" <?= $statut === 'accepté' ? 'selected' : '' ?>>accepté</option>
                    <option value="refusée" <?= $statut === 'refusée' ? 'selected' : '' ?>>refusée</option>
                    <option value="en préparation" <?= $statut === 'en préparation' ? 'selected' : '' ?>>en préparation</option>
                    <option value="en cours de livraison" <?= $statut === 'en cours de livraison' ? 'selected' : '' ?>>en cours de livraison</option>
                    <option value="livré" <?= $statut === 'livré' ? 'selected' : '' ?>>livré</option>
                    <option value="en attente du retour de matériel" <?= $statut === 'en attente du retour de matériel' ? 'selected' : '' ?>>en attente du retour de matériel</option>
                    <option value="terminée" <?= $statut === 'terminée' ? 'selected' : '' ?>>terminée</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Commandes</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Menu</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Changer statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($commandes)): ?>
                        <?php foreach ($commandes as $commande): ?>
                            <tr>
                                <td><?= htmlspecialchars($commande['numero_commande']) ?></td>
                                <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?><br><small><?= htmlspecialchars($commande['email']) ?></small></td>
                                <td><?= htmlspecialchars($commande['titre']) ?></td>
                                <td><?= htmlspecialchars($commande['date_prestation']) ?></td>
                                <td><?= number_format($commande['prix_total'], 2, ',', ' ') ?> €</td>
                                <td><?= htmlspecialchars($commande['statut']) ?></td>
                                <td>
                                    <form action="/actions/update_commande_statut_action.php" method="post">
                                        <input type="hidden" name="commande_id" value="<?= $commande['commande_id'] ?>">
                                        <select name="statut" class="form-select form-select-sm mb-2">
                                            <option value="en attente" <?= $commande['statut'] === 'en attente' ? 'selected' : '' ?>>en attente</option>
                                            <option value="accepté" <?= $commande['statut'] === 'accepté' ? 'selected' : '' ?>>accepté</option>
                                            <option value="refusée" <?= $commande['statut'] === 'refusée' ? 'selected' : '' ?>>refusée</option>
                                            <option value="en préparation" <?= $commande['statut'] === 'en préparation' ? 'selected' : '' ?>>en préparation</option>
                                            <option value="en cours de livraison" <?= $commande['statut'] === 'en cours de livraison' ? 'selected' : '' ?>>en cours de livraison</option>
                                            <option value="livré" <?= $commande['statut'] === 'livré' ? 'selected' : '' ?>>livré</option>
                                            <option value="en attente du retour de matériel" <?= $commande['statut'] === 'en attente du retour de matériel' ? 'selected' : '' ?>>en attente du retour de matériel</option>
                                            <option value="terminée" <?= $commande['statut'] === 'terminée' ? 'selected' : '' ?>>terminée</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-warning w-100">Mettre à jour</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucune commande trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (isset($_SESSION['utilisateur']['role']) && $_SESSION['utilisateur']['role'] === 'administrateur' && $ca): ?>
        <div class="card shadow-sm p-4 mb-4">
            <h2 class="h4 mb-3">Chiffre d’affaires</h2>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h3 class="h6 text-muted">Chiffre d’affaires total</h3>
                        <p class="fs-3 fw-bold mb-0">
                            <?= number_format((float)$ca['chiffre_affaires_total'], 2, ',', ' ') ?> €
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h3 class="h6 text-muted">Chiffre d’affaires réalisé</h3>
                        <p class="fs-3 fw-bold mb-0">
                            <?= number_format((float)$ca['chiffre_affaires_realise'], 2, ',', ' ') ?> €
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Ajouter un menu</h2>
        <form action="/actions/add_menu_action.php" method="post" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="titre" class="form-control" placeholder="Titre du menu" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="nombre_personne_minimum" class="form-control" placeholder="Min" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="prix_par_personne" class="form-control" placeholder="Prix" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="stock_disponible" class="form-control" placeholder="Stock" required>
            </div>
            <div class="col-md-6">
                <input type="number" name="theme_id" class="form-control" placeholder="Theme ID" required>
            </div>
            <div class="col-md-6">
                <input type="number" name="regime_id" class="form-control" placeholder="Regime ID" required>
            </div>
            <div class="col-md-6">
                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>
            <div class="col-md-6">
                <textarea name="conditions_menu" class="form-control" placeholder="Conditions" required></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Ajouter le menu</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Menus existants</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Min</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $menu): ?>
                        <tr>
                            <td><?= $menu['menu_id'] ?></td>
                            <td><?= htmlspecialchars($menu['titre']) ?></td>
                            <td><?= htmlspecialchars($menu['nombre_personne_minimum']) ?></td>
                            <td><?= htmlspecialchars($menu['prix_par_personne']) ?> €</td>
                            <td><?= htmlspecialchars($menu['stock_disponible']) ?></td>
                            <td>
                                <a href="/actions/delete_menu_action.php?id=<?= $menu['menu_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce menu ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Ajouter un plat</h2>
        <form action="/actions/add_plat_action.php" method="post" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="titre_plat" class="form-control" placeholder="Titre du plat" required>
            </div>
            <div class="col-md-4">
                <select name="type_plat" class="form-select" required>
                    <option value="entree">Entrée</option>
                    <option value="plat">Plat</option>
                    <option value="dessert">Dessert</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" step="0.01" name="prix" class="form-control" placeholder="Prix" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Ajouter le plat</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Plats existants</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Prix</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plats as $plat): ?>
                    <tr>
                        <td><?= $plat['plat_id'] ?></td>
                        <td><?= htmlspecialchars($plat['titre_plat']) ?></td>
                        <td><?= htmlspecialchars($plat['type_plat']) ?></td>
                        <td><?= htmlspecialchars($plat['prix']) ?> €</td>
                        <td>
                            <a href="/ctions/delete_plat_action.php?id=<?= $plat['plat_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce plat ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Ajouter un horaire</h2>
        <form action="/actions/add_horaire_action.php" method="post" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="jour" class="form-control" placeholder="Jour" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="heure_ouverture" class="form-control" placeholder="09:00" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="heure_fermeture" class="form-control" placeholder="18:00" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Ajouter l’horaire</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Horaires existants</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Jour</th>
                    <th>Ouverture</th>
                    <th>Fermeture</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horaires as $horaire): ?>
                    <tr>
                        <td><?= htmlspecialchars($horaire['jour']) ?></td>
                        <td><?= htmlspecialchars($horaire['heure_ouverture']) ?></td>
                        <td><?= htmlspecialchars($horaire['heure_fermeture']) ?></td>
                        <td>
                            <a href="/actions/delete_horaire_action.php?id=<?= $horaire['horaire_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet horaire ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card shadow-sm p-4">
        <h2 class="h4 mb-3">Validation des avis</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Commande</th>
                    <th>Client</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['numero_commande']) ?></td>
                        <td><?= htmlspecialchars($item['prenom'] . ' ' . $item['nom']) ?></td>
                        <td><?= htmlspecialchars($item['note']) ?>/5</td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td><?= htmlspecialchars($item['statut']) ?></td>
                        <td>
                            <a href="/actions/update_avis_statut_action.php?id=<?= $item['avis_id'] ?>&statut=valide" class="btn btn-sm btn-success mb-1">Valider</a>
                            <a href="/actions/update_avis_statut_action.php?id=<?= $item['avis_id'] ?>&statut=refuse" class="btn btn-sm btn-danger">Refuser</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>