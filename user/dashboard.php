<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once '../config/database.php';

$pageTitle = "Espace utilisateur - Vite & Gourmand";

$stmt = $pdo->prepare("
    SELECT c.*, m.titre
    FROM commande c
    INNER JOIN menu m ON c.menu_id = m.menu_id
    WHERE c.utilisateur_id = ?
    ORDER BY c.commande_id DESC
");
$stmt->execute([$_SESSION['utilisateur']['id']]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Mon espace utilisateur</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Opération effectuée avec succès.</div>
    <?php endif; ?>

    <div class="mb-4">
        <a href="/user/profile.php" class="btn btn-outline-primary">Modifier mon profil</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Commande</th>
                    <th>Menu</th>
                    <th>Date prestation</th>
                    <th>Personnes</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($commandes)): ?>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= htmlspecialchars($commande['numero_commande']) ?></td>
                            <td><?= htmlspecialchars($commande['titre']) ?></td>
                            <td><?= htmlspecialchars($commande['date_prestation']) ?></td>
                            <td><?= htmlspecialchars($commande['nombre_personne']) ?></td>
                            <td><?= number_format($commande['prix_total'], 2, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($commande['statut']) ?></td>
                            <td>
                                <a href="/user/commande-detail.php?id=<?= $commande['commande_id'] ?>" class="btn btn-sm btn-primary mb-1">Détail</a>

                                <?php if ($commande['statut'] !== 'accepté'): ?>
                                    <a href="/user/commande-detail.php?id=<?= $commande['commande_id'] ?>&edit=1" class="btn btn-sm btn-warning mb-1">Modifier</a>
                                    <a href="/actions/cancel_commande_action.php?id=<?= $commande['commande_id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Confirmer l’annulation ?')">Annuler</a>
                                <?php endif; ?>

                                <?php if ($commande['statut'] === 'terminée'): ?>
                                    <a href="/user/commande-detail.php?id=<?= $commande['commande_id'] ?>#avis" class="btn btn-sm btn-success">Donner un avis</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucune commande pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>