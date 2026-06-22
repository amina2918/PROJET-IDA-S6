<?php
require_once("includes/db.php");
require_once("includes/functions.php");

// Récupérer les catégories et produits
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
$produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll();

include("includes/header.php");
?>

<!-- Hero Section Modern -->
<section class="hero-section" id="hero">
    <h1>Bienvenue chez E-TECH-Boutique</h1>
    <p>Votre destination pour les meilleurs matériels électroniques : smarphones , écouteurs , accessoires et plus encore</p>
    <a href="#categories" class="btn hero-btn">
        <i class="bi bi-arrow-down me-2"></i>Explorer les Produits
    </a>
</section>

<!-- Catégories Filter -->
<section id="categories">
    <h2 class="section-title">Nos Catégories</h2>
    
    <div class="categories-filter">
        <button class="cat-btn active" onclick="filterByCategory('all')">
            <i class="bi bi-grid-1x2 me-2"></i>Tous les produits
        </button>
        <?php foreach($categories as $cat): ?>
            <button class="cat-btn" onclick="filterByCategory('<?= $cat['id'] ?>')">
                <i class="bi bi-tag me-2"></i><?= e($cat['nom']) ?>
            </button>
        <?php endforeach; ?>
    </div>
</section>

<!-- Produits Grid -->
<section>
    <div class="products-grid" id="produits">
        <?php foreach($produits as $prod): ?>
            <div class="product-card" data-category="<?= $prod['categorie_id'] ?>">
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

<script>
function filterByCategory(categoryId) {
    const products = document.querySelectorAll('.product-card');
    const buttons = document.querySelectorAll('.cat-btn');
    
    // Activer le bouton approprié
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.closest('.cat-btn').classList.add('active');
    
    // Filtrer les produits
    products.forEach(product => {
        if (categoryId === 'all' || product.dataset.category === categoryId) {
            product.style.display = '';
            setTimeout(() => product.classList.add('fadeIn'), 10);
        } else {
            product.style.display = 'none';
            product.classList.remove('fadeIn');
        }
    });
}
</script>

<?php include("includes/footer.php"); ?>