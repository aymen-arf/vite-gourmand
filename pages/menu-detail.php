<?php
$pageTitle = "Détail du menu - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <div class="row g-5">
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1200&q=80" class="img-fluid rounded shadow" alt="Menu détail">
        </div>
        <div class="col-md-6">
            <h1>Menu Noël Festif</h1>
            <p class="text-muted">Thème : Noël | Régime : Classique</p>
            <p>
                Ce menu comprend une entrée, un plat et un dessert, pensés pour un repas festif
                et convivial. Il convient parfaitement aux prestations de fin d’année.
            </p>

            <ul class="list-group mb-4">
                <li class="list-group-item">Entrée : Foie gras et chutney</li>
                <li class="list-group-item">Plat : Suprême de volaille, gratin dauphinois</li>
                <li class="list-group-item">Dessert : Bûche artisanale</li>
                <li class="list-group-item">Allergènes : lait, œufs</li>
                <li class="list-group-item">Minimum de personnes : 10</li>
                <li class="list-group-item">Prix : 25 € / personne</li>
                <li class="list-group-item">Stock disponible : 5 commandes</li>
            </ul>

            <div class="condition-box mb-4">
                <strong>Conditions importantes :</strong>
                commande à passer au moins 7 jours avant la prestation.
                Conservation au frais recommandée jusqu’à la livraison.
            </div>

            <a href="#" class="btn btn-success btn-lg">Commander ce menu</a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>