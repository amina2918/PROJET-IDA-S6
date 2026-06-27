<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");
check_admin();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_statut'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Requête invalide.");
    }
    
    $id = (int)$_POST['id'];
    $statut = clean_input($_POST['statut']);

    $stmt = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $id]);
    
    header("Location: suivi_commandes.php?message=update");
    exit();
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$start = ($page - 1) * $per_page;

// Récupérer le nombre total de commandes
$total = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$pages = ceil($total / $per_page);

// Récupérer les commandes
$stmt = $pdo->prepare("SELECT * FROM commandes ORDER BY date_commande DESC LIMIT ? OFFSET ?");
$stmt->execute([$per_page, $start]);
$commandes = $stmt->fetchAll();
?>

<?php include("nav_admin.php"); ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h5>
            <i class="bi bi-receipt"></i> Suivi des Commandes
        </h5>
        <span class="badge bg-light text-dark"><?= $total ?></span>
    </div>

    <div class="admin-card-body">
        <!-- Messages -->
        <?php if (isset($_GET['message']) && $_GET['message'] === 'update'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i> Statut mis à jour
            </div>
        <?php endif; ?>

        <!-- Tableau des commandes -->
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $cmd): ?>
                            <tr>
                                <td>
                                    <strong><?= e($cmd['nom_client']) ?></strong>
                                </td>
                                <td>
                                    <a href="https://wa.me/<?= str_replace([' ', '+', '-'], '', $cmd['telephone']) ?>" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-whatsapp"></i> <?= e($cmd['telephone']) ?>
                                    </a>
                                </td>
                                <td>
                                    <strong><?= format_price($cmd['total']) ?></strong>
                                </td>
                                <td>
                                    <span class="table-status <?= strtolower(str_replace(' ', '-', $cmd['statut'])) ?>">
                                        <?= e($cmd['statut']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $cmd['id'] ?>">
                                        <i class="bi bi-eye"></i> Détails
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statutModal" 
                                            onclick="setCommandeId(<?= $cmd['id'] ?>, '<?= e($cmd['statut']) ?>')">
                                        <i class="bi bi-arrow-repeat"></i> Statut
                                    </button>
                                </td>
                            </tr>

                            <!-- MODAL DÉTAILS -->
                            <div class="modal fade" id="detailsModal<?= $cmd['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Commande #<?= $cmd['id'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Infos Client</h6>
                                            <p>
                                                <strong>Nom:</strong> <?= e($cmd['nom_client']) ?><br>
                                                <strong>Téléphone:</strong> <?= e($cmd['telephone']) ?><br>
                                                <strong>Adresse:</strong> <?= e($cmd['adresse']) ?>
                                            </p>

                                            <h6 class="mt-3">Produits</h6>
                                            <p><?= nl2br(e($cmd['produits'])) ?></p>

                                            <h6 class="mt-3">Contrôle</h6>
                                            <p>
                                                <strong>Total:</strong> <?= format_price($cmd['total']) ?><br>
                                                <strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?><br>
                                                <strong>Statut:</strong> <span class="table-status <?= strtolower(str_replace(' ', '-', $cmd['statut'])) ?>"><?= e($cmd['statut']) ?></span>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="https://wa.me/<?= str_replace([' ', '+', '-'], '', $cmd['telephone']) ?>" target="_blank" class="btn btn-success">
                                                <i class="bi bi-whatsapp me-2"></i>Contacter
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <h4 class="mt-3">Aucune commande</h4>
                <p class="text-muted">Les commandes apparaîtront ici</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL CHANGEMENT STATUT -->
<div class="modal fade" id="statutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-arrow-repeat me-2"></i>Changer le Statut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="id" id="commandeId">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nouveau Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="En attente">🕐 En attente</option>
                            <option value="Confirmée">✓ Confirmée</option>
                            <option value="En cours de livraison">📦 En cours de livraison</option>
                            <option value="Livrée">✓✓ Livrée</option>
                            <option value="Annulée">✗ Annulée</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="update_statut" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Mettre à jour
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
function setCommandeId(id) {
    document.getElementById('commandeId').value = id;
}
</script>

</body>
</html>