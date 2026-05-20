<?php
$pageTitle = "Accueil - Vite & Gourmand";
include 'includes/header.php';
?>

<section class="hero">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">Vite & Gourmand</h1>
        <p class="lead">Votre traiteur pour tous vos événements à Bordeaux.</p>
        <a href="/vite-gourmand/pages/menus.php" class="btn btn-light btn-lg mt-3">Voir les menus</a>
    </div>
</section>

<section class="container py-5">
    <h2 class="section-title">Présentation de l’entreprise</h2>
    <p>
        Vite & Gourmand est une entreprise familiale spécialisée dans la préparation de menus
        pour différents événements : Noël, Pâques, repas classiques et prestations sur mesure.
    </p>
    <p>
        L’équipe met en avant la qualité des produits, le professionnalisme du service et
        une organisation adaptée aux besoins des clients.
    </p>
</section>

<section class="container pb-5">
    <h2 class="section-title">Avis clients validés</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card review-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Marie</h5>
                    <p class="card-text">Excellent service, menu délicieux et livraison très professionnelle.</p>
                    <p class="text-warning mb-0">★★★★★</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card review-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Lucas</h5>
                    <p class="card-text">Très bonne organisation pour notre événement, je recommande.</p>
                    <p class="text-warning mb-0">★★★★★</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card review-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Sophie</h5>
                    <p class="card-text">Des plats de qualité et un excellent rapport qualité-prix.</p>
                    <p class="text-warning mb-0">★★★★☆</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>