<?php
require_once("includes/db.php");
require_once("includes/functions.php");

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$produits = [];

if (!empty($query)) {
    // Recherche par nom ou description
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE nom LIKE ? OR description LIKE ? ORDER BY nom ASC");
    $searchTerm = '%' . $query . '%';
    $stmt->execute([$searchTerm, $searchTerm]);
    $produits = $stmt->fetchAll();
}

include("includes/header.php");
?>

<div class="container-lg py-4">
    <!-- Titre -->
    <div class="mb-4">
        <h2 class="section-title">
            <i class="bi bi-search me-2"></i>Résultats de Recherche
        </h2>
        <p class="text-muted">
            <?php if (!empty($query)): ?>
                Résultats pour "<strong><?= e($query) ?></strong>"
            <?php else: ?>
                Entrez un terme de recherche pour trouver des produits
            <?php endif; ?>
        </p>
    </div>

    <!-- Barre de Recherche -->
    <div class="row mb-4">
        <div class="col-lg-6 mx-auto">
            <form action="recherche.php" method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="q" placeholder="Rechercher des produits..." value="<?= e($query) ?>" required>
                    <button class="btn btn-search" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <div>
        <?php if (empty($query)): ?>
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <h3>Aucune recherche effectuée</h3>
                <p>Utilisez la barre de recherche ci-dessus pour trouver vos produits préférés</p>
                <a href="index.php" class="btn btn-primary mt-3">
                    <i class="bi bi-shop me-2"></i>Voir tous les produits
                </a>
            </div>
        <?php elseif (empty($produits)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>Aucun produit trouvé</h3>
                <p>Nous n'avons trouvé aucun produit correspondant à "<strong><?= e($query) ?></strong>"</p>
                <a href="index.php" class="btn btn-primary mt-3">
                    <i class="bi bi-chevron-left me-2"></i>Retour à l'accueil
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-info border-0 mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong><?= count($produits) ?></strong> produit(s) trouvé(s)
            </div>

            <div class="products-grid" data-aos="fade-up">
                <?php foreach($produits as $prod): ?>
                    <div class="product-card">
                        <img src="uploads/<?= e($prod['image']) ?>" alt="<?= e($prod['nom']) ?>" class="product-img">
                        
                        <div class="product-info">
                            <h6 class="product-name"><?= e($prod['nom']) ?></h6>
                            <div class="product-price"><?= format_price($prod['prix']) ?></div>
                            
                            <div class="product-actions">
                                <a href="produits.php?id=<?= $prod['id'] ?>" class="btn btn-view">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
                                
                                <button class="btn btn-primary add-to-cart"
                                    data-id="<?= $prod['id'] ?>"
                                    data-nom="<?= e($prod['nom']) ?>"
                                    data-prix="<?= $prod['prix'] ?>"
                                    data-image="<?= e($prod['image']) ?>">
                                    <i class="bi bi-cart-plus me-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>