<?php
$pageTitle = "Contact - Vite & Gourmand";
include '../includes/header.php';
?>

<section class="container">
    <h1 class="section-title">Contact</h1>

    <div class="row">
        <div class="col-lg-8">
            <form action="#" method="post" class="card shadow-sm p-4">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>