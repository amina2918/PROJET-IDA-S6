<?php
require_once("includes/db.php");
require_once("includes/functions.php");

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$_GET['id']]);
$produit = $stmt->fetch();

if (!$produit) {
    redirect("index.php");
}

// Récupérer les produits similaires
$stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie_id = ? AND id != ? LIMIT 4");
$stmt->execute([$produit['categorie_id'], $produit['id']]);
$produits_similaires = $stmt->fetchAll();

include("includes/header.php");
?>

<div class="container-lg py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item active"><?= e($produit['nom']) ?></li>
        </ol>
    </nav>

    <!-- Produit Details -->
    <div class="row mb-5">
        <!-- Image -->
        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
            <div class="product-detail-img">
                <img src="uploads/<?= e($produit['image']) ?>" alt="<?= e($produit['nom']) ?>" class="w-100 rounded">
            </div>
        </div>

        <!-- Info Produit -->
        <div class="col-lg-6" data-aos="fade-left">
            <h2 class="mb-3 fw-bold"><?= e($produit['nom']) ?></h2>
            
            <div class="mb-3">
                <span class="badge bg-info">Disponible</span>
            </div>

            <div class="mb-4">
                <h3 class="product-detail-price"><?= format_price($produit['prix']) ?></h3>
            </div>

            <div class="mb-4">
                <h5>Description</h5>
                <p class="text-muted"><?= nl2br(e($produit['description'])) ?></p>
            </div>

            <!-- Actions -->
            <div class="product-detail-actions mb-4">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary btn-lg add-to-cart flex-grow-1"
                        data-id="<?= $produit['id'] ?>"
                        data-nom="<?= e($produit['nom']) ?>"
                        data-prix="<?= $produit['prix'] ?>"
                        data-image="<?= e($produit['image']) ?>">
                        <i class="bi bi-cart-plus me-2"></i>Ajouter au Panier
                    </button>
                    
                    <a href="panier.php" class="btn btn-outline-primary btn-lg flex-grow-1">
                        <i class="bi bi-bag-check me-2"></i>Voir le Panier
                    </a>
                </div>
            </div>

            <!-- Info Produit -->
            <div class="alert alert-light border" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Infos Utiles</strong>
                <ul class="mb-0 mt-2 small">
                    <li>Satisfaction 100% garantie</li>
                    <li>Support client 24/7</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Produits Similaires -->
    <?php if (!empty($produits_similaires)): ?>
        <hr class="my-5">
        
        <section>
            <h3 class="section-title mb-4">Produits Similaires</h3>
            
            <div class="products-grid">
                <?php foreach($produits_similaires as $prod): ?>
                    <div class="product-card" data-aos="fade-up">
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
        </section>
    <?php endif; ?>
</div>

<style>
.product-detail-img {
    background: var(--secondary);
    padding: 1rem;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.product-detail-price {
    color: var(--primary);
    font-weight: 700;
    font-size: 2rem;
}

.product-detail-actions .btn {
    padding: 1rem 1.5rem;
    font-weight: 600;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}
</style>

<?php include("includes/footer.php"); ?>