<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");
check_admin();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$start = ($page - 1) * $per_page;

// Récupérer le nombre total de produits
$total = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
$pages = ceil($total / $per_page);

// Récupérer les produits
$stmt = $pdo->prepare("SELECT p.*, c.nom as categorie_nom FROM produits p 
                       LEFT JOIN categories c ON p.categorie_id = c.id 
                       ORDER BY p.id DESC LIMIT ? OFFSET ?");
$stmt->execute([$per_page, $start]);
$produits = $stmt->fetchAll();

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

// Traiter la suppression
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM produits WHERE id = ?")->execute([$id]);
    header("Location: produits.php?message=delete");
    exit();
}
?>

<?php include("nav_admin.php"); ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="bi bi-box-seam"></i> Gestion des Produits</h5>
        <a href="ajouter_produit.php" class="btn btn-sm btn-light">
            <i class="bi bi-plus-lg me-1"></i> Nouveau Produit
        </a>
    </div>

    <div class="admin-card-body">
        <!-- Messages -->
        <?php if (isset($_GET['message'])): ?>
            <?php if ($_GET['message'] === 'delete'): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> Produit supprimé avec succès
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Tableau des produits -->
        <?php if (!empty($produits)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $prod): ?>
                            <tr>
                                <td>
                                    <img src="../uploads/<?= e($prod['image']) ?>" alt="<?= e($prod['nom']) ?>" class="table-img">
                                </td>
                                <td>
                                    <strong><?= e($prod['nom']) ?></strong><br>
                                    <small class="text-muted"><?= substr(e($prod['description']), 0, 50) ?>...</small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= $prod['categorie_nom'] ?? 'N/A' ?></span>
                                </td>
                                <td>
                                    <strong><?= format_price($prod['prix']) ?></strong>
                                </td>
                                <td>
                                    <a href="editer_produit.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-info btn-icon">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?action=delete&id=<?= $prod['id'] ?>" class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Êtes-vous sûr?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">Précédent</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">Suivant</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <h4 class="mt-3">Aucun produit trouvé</h4>
                <p class="text-muted">Commencez par ajouter un nouveau produit</p>
                <a href="ajouter_produit.php" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Ajouter un produit
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

    </div> <!-- Fin admin-main -->
</div> <!-- Fin admin-layout -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
