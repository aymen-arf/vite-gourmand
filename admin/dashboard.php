<?php
session_start();

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'administrateur') {
    header('Location: /pages/login.php');
    exit;
}

require_once '../config/database.php';
require_once '../config/mongodb.php';

$pageTitle = "Espace administrateur - Vite & Gourmand";

$employes = $pdo->query("
    SELECT u.*, r.libelle AS role_nom
    FROM utilisateur u
    INNER JOIN role r ON u.role_id = r.role_id
    WHERE r.libelle = 'employe'
    ORDER BY u.utilisateur_id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$statsMenus = [];

if ($mongoStats) {
    $pipeline = [
    [
        '$group' => [
            '_id' => '$menu',
            'total' => ['$sum' => 1]
        ]
    ],
    [
        '$sort' => ['total' => -1]
    ]
];

    $results = $mongoStats->aggregate($pipeline);

    foreach ($results as $row) {
        $statsMenus[] = [
            'menu' => $row->_id,
            'total' => $row->total
        ];
    }
}

include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Espace administrateur</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Opération réalisée avec succès.</div>
    <?php endif; ?>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Créer un compte employé</h2>
        <form action="/actions/create_employe_action.php" method="post" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nom" class="form-control" placeholder="Nom" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="prenom" class="form-control" placeholder="Prénom" required>
            </div>
            <div class="col-md-4">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="telephone" class="form-control" placeholder="Téléphone" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="ville" class="form-control" placeholder="Ville" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="pays" class="form-control" placeholder="Pays" required>
            </div>
            <div class="col-md-8">
                <input type="text" name="adresse_postale" class="form-control" placeholder="Adresse postale" required>
            </div>
            <div class="col-md-4">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Créer le compte employé</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Gérer les employés</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actif</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employes)): ?>
                        <?php foreach ($employes as $employe): ?>
                            <tr>
                                <td><?= htmlspecialchars($employe['prenom'] . ' ' . $employe['nom']) ?></td>
                                <td><?= htmlspecialchars($employe['email']) ?></td>
                                <td><?= htmlspecialchars($employe['telephone']) ?></td>
                                <td><?= $employe['actif'] ? 'Oui' : 'Non' ?></td>
                                <td>
                                    <?php if ($employe['actif']): ?>
                                        <a href="/actions/toggle_employe_action.php?id=<?= $employe['utilisateur_id'] ?>&actif=0" class="btn btn-sm btn-danger">Désactiver</a>
                                    <?php else: ?>
                                        <a href="/actions/toggle_employe_action.php?id=<?= $employe['utilisateur_id'] ?>&actif=1" class="btn btn-sm btn-success">Réactiver</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucun employé trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h4 mb-3">Statistiques des commandes par menu</h2>

        <?php if (!empty($statsMenus)): ?>
            <canvas id="statsChart" height="120"></canvas>

            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Menu</th>
                            <th>Nombre de commandes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statsMenus as $stat): ?>
                            <tr>
                                <td><?= htmlspecialchars($stat['menu']) ?></td>
                                <td><?= htmlspecialchars($stat['total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Aucune statistique MongoDB disponible pour le moment.</p>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm p-4">
        <h2 class="h4 mb-3">Accès au dashboard employé</h2>
        <a href="/employee/dashboard.php" class="btn btn-outline-dark">Ouvrir les fonctions employé</a>
    </div>
</section>

<?php if (!empty($statsMenus)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('statsChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($statsMenus, 'menu')) ?>,
        datasets: [{
            label: 'Nombre de commandes',
            data: <?= json_encode(array_column($statsMenus, 'total')) ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.7)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>