<?php
$pageTitle = "Menus - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Nos menus</h1>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <label class="form-label">Prix maximum</label>
            <input type="number" class="form-control" placeholder="Ex : 50">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Thème</label>
            <select class="form-select">
                <option>Tous</option>
                <option>Noël</option>
                <option>Pâques</option>
                <option>Classique</option>
                <option>Événement</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Régime</label>
            <select class="form-select">
                <option>Tous</option>
                <option>Classique</option>
                <option>Végétarien</option>
                <option>Vegan</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Nombre minimum de personnes</label>
            <input type="number" class="form-control" placeholder="Ex : 10">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card menu-card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Menu Noël">
                <div class="card-body">
                    <h5 class="card-title">Menu Noël Festif</h5>
                    <p class="card-text">Un menu complet pour célébrer les fêtes avec des produits raffinés.</p>
                    <p><strong>Minimum :</strong> 10 personnes</p>
                    <p><strong>Prix :</strong> 25 € / personne</p>
                    <a href="/vite-gourmand/pages/menu-detail.php?id=1" class="btn btn-primary">Voir le détail</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card menu-card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Menu Classique">
                <div class="card-body">
                    <h5 class="card-title">Menu Classique</h5>
                    <p class="card-text">Une formule adaptée aux repas familiaux et événements traditionnels.</p>
                    <p><strong>Minimum :</strong> 8 personnes</p>
                    <p><strong>Prix :</strong> 20 € / personne</p>
                    <a href="/vite-gourmand/pages/menu-detail.php?id=2" class="btn btn-primary">Voir le détail</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card menu-card shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Menu Vegan">
                <div class="card-body">
                    <h5 class="card-title">Menu Vegan Événement</h5>
                    <p class="card-text">Une proposition végétale adaptée aux événements modernes et responsables.</p>
                    <p><strong>Minimum :</strong> 12 personnes</p>
                    <p><strong>Prix :</strong> 27 € / personne</p>
                    <a href="/vite-gourmand/pages/menu-detail.php?id=3" class="btn btn-primary">Voir le détail</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>