<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");
check_admin();

// Statistiques
$totalProduits = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
$totalCommandes = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$enAttente = $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut='En attente'")->fetchColumn();
$livrees = $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut='Livrée'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total) FROM commandes WHERE statut='Livrée'")->fetchColumn() ?: 0;

// Dernières commandes
$commandes = $pdo->query("SELECT * FROM commandes ORDER BY date_commande DESC LIMIT 5")->fetchAll();

// Produits récents
$produits_recents = $pdo->query("SELECT * FROM produits ORDER BY id DESC LIMIT 5")->fetchAll();
?>

<?php include("nav_admin.php"); ?>

<!-- MAIN CONTENT -->
<div class="mb-4">
    <h2 class="mb-4">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </h2>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-number"><?= $totalProduits ?></div>
                <div class="stat-label">Produits</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-number"><?= $totalCommandes ?></div>
                <div class="stat-label">Commandes</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-number"><?= $enAttente ?></div>
                <div class="stat-label">En Attente</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="stat-number"><?= number_format($totalRevenue/1, 0, ',', ' ') ?></div>
                <div class="stat-label">Revenus D</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Catégories -->
        <div class="col-lg-6 mb-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-tags me-2"></i>Catégories</h5>
                    <span class="badge bg-light text-dark"><?= $totalCategories ?></span>
                </div>
                <div class="admin-card-body">
                    <a href="categories.php" class="btn btn-primary w-100">
                        <i class="bi bi-pencil me-2"></i>Gérer les catégories
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="col-lg-6 mb-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-lightning me-2"></i>Actions Rapides</h5>
                </div>
                <div class="admin-card-body">
                    <div class="d-grid gap-2">
                        <a href="ajouter_produit.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Ajouter un produit
                        </a>
                        <a href="produits.php" class="btn btn-info">
                            <i class="bi bi-list me-2"></i>Tous les produits
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières Commandes -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-receipt me-2"></i>Dernières Commandes</h5>
            <a href="suivi_commandes.php" class="btn btn-sm btn-light">Voir tout →</a>
        </div>

        <div class="admin-card-body">
            <?php if (!empty($commandes)): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $cmd): ?>
                                <tr>
                                    <td><?= e($cmd['nom_client']) ?></td>
                                    <td><?= e($cmd['telephone']) ?></td>
                                    <td><?= format_price($cmd['total']) ?></td>
                                    <td>
                                        <span class="table-status <?= strtolower(str_replace(' ', '-', $cmd['statut'])) ?>">
                                            <?= e($cmd['statut']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-4">Aucune commande</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Produits Récents -->
    <div class="admin-card mt-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-star me-2"></i>Produits Récents</h5>
            <a href="produits.php" class="btn btn-sm btn-light">Voir tout →</a>
        </div>

        <div class="admin-card-body">
            <?php if (!empty($produits_recents)): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits_recents as $prod): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/<?= e($prod['image']) ?>" alt="<?= e($prod['nom']) ?>" class="table-img">
                                    </td>
                                    <td>
                                        <strong><?= e($prod['nom']) ?></strong>
                                    </td>
                                    <td><?= format_price($prod['prix']) ?></td>
                                    <td>
                                        <a href="editer_produit.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

    </div> <!-- Fin admin-main -->
</div> <!-- Fin admin-layout -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>