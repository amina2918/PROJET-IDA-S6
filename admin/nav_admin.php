<?php
// Déterminer la page active
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - E-Tech-Boutique</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<!-- NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-speedometer2"></i>
            E-Tech-boutique-Admin
        </a>

        <!-- Toggle Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <i class="bi bi-list"></i>
        </button>

        <!-- Menu Items -->
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-house-door me-2"></i>Dashboard
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($current_page, ['produits.php', 'ajouter_produit.php']) ? 'active' : '' ?>" href="#" id="navProduits" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-box me-2"></i>Produits
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navProduits">
                        <li><a class="dropdown-item" href="produits.php"><i class="bi bi-list me-2"></i>Tous les produits</a></li>
                        <li><a class="dropdown-item" href="ajouter_produit.php"><i class="bi bi-plus-circle me-2"></i>Ajouter produit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="categories.php"><i class="bi bi-tag me-2"></i>Catégories</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'suivi_commandes.php') ? 'active' : '' ?>" href="suivi_commandes.php">
                        <i class="bi bi-cart-check me-2"></i>Commandes
                    </a>
                </li>
            </ul>

            <!-- Right Side -->
            <div class="d-flex align-items-center gap-2">
                <div class="admin-user">
                    <i class="bi bi-person-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- LAYOUT CONTAINER -->
<div class="admin-layout">
    <!-- SIDEBAR -->
    <div class="admin-sidebar d-none d-lg-block">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
                    <i class="bi bi-graph-up"></i>Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($current_page === 'produits.php') ? 'active' : '' ?>" href="produits.php">
                    <i class="bi bi-box-seam"></i>Produits
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($current_page === 'ajouter_produit.php') ? 'active' : '' ?>" href="ajouter_produit.php">
                    <i class="bi bi-plus-lg"></i>Ajouter Produit
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($current_page === 'categories.php') ? 'active' : '' ?>" href="categories.php">
                    <i class="bi bi-tags"></i>Catégories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($current_page === 'suivi_commandes.php') ? 'active' : '' ?>" href="suivi_commandes.php">
                    <i class="bi bi-receipt"></i>Commandes
                </a>
            </li>

            <hr class="my-3">

            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-door-left"></i>Déconnexion
                </a>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="admin-main">