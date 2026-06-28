<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");
check_admin();

if (!isset($_GET['id'])) {
    redirect("produits.php");
}

$id = (int)$_GET['id'];

// Récupérer le produit
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    redirect("produits.php");
}

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

// Traiter la mise à jour
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Requête invalide.");
    }

    $nom = clean_input($_POST['nom']);
    $categorie = (int)$_POST['categorie'];
    $description = clean_input($_POST['description']);
    $prix = (float)$_POST['prix'];

    // Gérer l'image
    $image = $produit['image'];
    if (!empty($_FILES['image']['name'])) {
        $nouvelle_image = upload_image($_FILES['image']);
        if ($nouvelle_image) {
            $image = $nouvelle_image;
        }
    }

    $stmt = $pdo->prepare("UPDATE produits SET nom = ?, categorie_id = ?, description = ?, prix = ?, image = ? WHERE id = ?");
    $stmt->execute([$nom, $categorie, $description, $prix, $image, $id]);

    header("Location: produits.php?message=update");
    exit();
}
?>

<?php include("nav_admin.php"); ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>
            <i class="bi bi-pencil"></i> Éditer Produit
        </h5>
        <a href="produits.php" class="btn btn-sm btn-light">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="admin-card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

            <div class="row">
                <div class="col-lg-8">
                    <!-- Nom -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-type me-2"></i>Nom du Produit
                        </label>
                        <input type="text" name="nom" class="form-control" value="<?= e($produit['nom']) ?>" required>
                    </div>

                    <!-- Catégorie -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-tag me-2"></i>Catégorie
                        </label>
                        <select name="categorie" class="form-control" required>
                            <option value="">-- Sélectionner une catégorie --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $produit['categorie_id']) ? 'selected' : '' ?>>
                                    <?= e($cat['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-chat-left-text me-2"></i>Description
                        </label>
                        <textarea name="description" class="form-control" required><?= e($produit['description']) ?></textarea>
                    </div>

                    <!-- Prix -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-cash-coin me-2"></i>Prix (FCFA)
                        </label>
                        <input type="number" name="prix" class="form-control" value="<?= $produit['prix'] ?>" step="100" required>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Image Actuelle -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-image me-2"></i>Image Actuelle
                        </label>
                        <div class="card border-0 bg-light">
                            <img src="../uploads/<?= e($produit['image']) ?>" alt="<?= e($produit['nom']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Nouvelle Image -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-upload me-2"></i>Changer l'Image
                        </label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">JPG, PNG, WebP (max 2MB)</small>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-lg me-2"></i> Enregistrer les modifications
                </button>
                <a href="produits.php" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-lg me-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

    </div> <!-- Fin admin-main -->
</div> <!-- Fin admin-layout -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>