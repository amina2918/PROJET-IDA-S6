<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");
check_admin();

// Récupérer les catégories
$categories = $pdo->query("SELECT c.*, COUNT(p.id) as nb_produits FROM categories c 
                          LEFT JOIN produits p ON p.categorie_id = c.id 
                          GROUP BY c.id ORDER BY c.nom")->fetchAll();

// Traiter l'ajout/modification
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Requête invalide.");
    }

    $nom = clean_input($_POST['nom']);
    $action = $_POST['action'] ?? 'add';

    if ($action === 'add') {
        $pdo->prepare("INSERT INTO categories (nom) VALUES (?)")->execute([$nom]);
        header("Location: categories.php?message=add");
    } elseif ($action === 'edit') {
        $id = (int)$_POST['id'];
        $pdo->prepare("UPDATE categories SET nom = ? WHERE id = ?")->execute([$nom, $id]);
        header("Location: categories.php?message=edit");
    }
    exit();
}

// Traiter la suppression
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Vérifier que la catégorie n'a pas de produits
    $count = $pdo->prepare("SELECT COUNT(*) FROM produits WHERE categorie_id = ?")->execute([$id]);
    if ($count == 0) {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        header("Location: categories.php?message=delete");
    } else {
        header("Location: categories.php?message=error");
    }
    exit();
}
?>

<?php include("nav_admin.php"); ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="bi bi-tags"></i> Gestion des Catégories</h5>
        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle Catégorie
        </button>
    </div>

    <div class="admin-card-body">
        <!-- Messages -->
        <?php if (isset($_GET['message'])): ?>
            <?php if ($_GET['message'] === 'add'): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> Catégorie ajoutée
                </div>
            <?php elseif ($_GET['message'] === 'edit'): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> Catégorie mise à jour
                </div>
            <?php elseif ($_GET['message'] === 'delete'): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> Catégorie supprimée
                </div>
            <?php elseif ($_GET['message'] === 'error'): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i> Cette catégorie contient des produits
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Tableau des catégories -->
        <?php if (!empty($categories)): ?>
            <div class="row">
                <?php foreach ($categories as $cat): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="bi bi-folder me-2"></i><?= e($cat['nom']) ?>
                                </h6>
                                <p class="card-text text-muted">
                                    <small><?= $cat['nb_produits'] ?> produit(s)</small>
                                </p>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editCategoryModal" 
                                            onclick="editCategory(<?= $cat['id'] ?>, '<?= e($cat['nom']) ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="?action=delete&id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Êtes-vous sûr?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <h4 class="mt-3">Aucune catégorie</h4>
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg me-2"></i>Ajouter une catégorie
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL AJOUT -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-lg me-2"></i>Nouvelle Catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="action" value="add">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nom de la catégorie</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: pochettes" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ÉDITION -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Éditer Catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editCatId">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nom de la catégorie</label>
                        <input type="text" name="nom" class="form-control" id="editCatNom" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div> <!-- Fin admin-main -->
</div> <!-- Fin admin-layout -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editCategory(id, nom) {
    document.getElementById('editCatId').value = id;
    document.getElementById('editCatNom').value = nom;
}
</script>

</body>
</html>