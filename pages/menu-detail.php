<?php
session_start();
require_once '../config/database.php';

$menuId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menuId]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = "Détail du menu - Vite & Gourmand";
include '../includes/header.php';

if (!$menu) {
?>
    <section class="container">
        <div class="alert alert-danger">
            Menu introuvable.
        </div>
        <a href="/vite-gourmand/pages/menus.php" class="btn btn-primary">Retour aux menus</a>
    </section>
<?php
    include '../includes/footer.php';
    exit;
}
?>

<section class="container">
    <div class="row g-5">
        <div class="col-md-6">
            <img
                src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1200&q=80"
                class="img-fluid rounded shadow"
                alt="<?= htmlspecialchars($menu['titre']) ?>"
            >
        </div>

        <div class="col-md-6">
            <h1><?= htmlspecialchars($menu['titre']) ?></h1>

            <p class="text-muted">
                Description du menu
            </p>

            <p>
                <?= htmlspecialchars($menu['description']) ?>
            </p>

            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>Nombre minimum de personnes :</strong>
                    <?= htmlspecialchars($menu['nombre_personne_minimum']) ?>
                </li>
                <li class="list-group-item">
                    <strong>Prix par personne :</strong>
                    <?= htmlspecialchars($menu['prix_par_personne']) ?> €
                </li>
                <li class="list-group-item">
                    <strong>Stock disponible :</strong>
                    <?= htmlspecialchars($menu['stock_disponible']) ?>
                </li>
            </ul>

            <div class="condition-box mb-4">
                <strong>Conditions importantes :</strong><br>
                <?= nl2br(htmlspecialchars($menu['conditions_menu'])) ?>
            </div>

            <?php if (isset($_SESSION['utilisateur'])): ?>
                <a href="/vite-gourmand/pages/commande.php?menu_id=<?= urlencode($menu['menu_id']) ?>" class="btn btn-success btn-lg">
                    Commander ce menu
                </a>
            <?php else: ?>
                <a href="/vite-gourmand/pages/login.php" class="btn btn-primary btn-lg">
                    Se connecter pour commander
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>