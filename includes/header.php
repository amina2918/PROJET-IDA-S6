<?php
require_once("includes/db.php");
require_once("includes/functions.php");

// Récupérer les catégories pour le menu
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
$is_admin = isset($_SESSION['admin_id']) ? true : false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tech-Boutique ACCESSOIRES & TELEPHONIQUES </title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
</head>

<body>

<!-- Header / Navigation -->
<header class="navbar-header">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-3 px-lg-4">
            <!-- Logo -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="assets/images/logo.png.jpeg" alt="E-Tech-Boutique" class="logo-img me-2" style="height: 50px;">
                <span class="d-none d-sm-inline">E-Tech-Boutique 🛍️</span>
            </a>

            <!-- Search Bar Desktop -->
            <form class="d-none d-lg-flex search-form flex-grow-1 mx-4" action="recherche.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control search-input" placeholder="Chercher un produit..." name="q" required>
                    <button class="btn btn-search" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right Side Desktop -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                
                
                <a href="panier.php" class="btn cart-btn position-relative">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge" id="cart-count">0</span>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <!-- Collapsible Menu -->
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="container-fluid px-3 px-lg-4 py-3">
                <!-- Mobile Search -->
                <form class="d-lg-none mb-3" action="recherche.php" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control search-input" placeholder="Chercher..." name="q" required>
                        <button class="btn btn-search" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav">
                    <!-- Accueil -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house me-2"></i>Accueil
                        </a>
                    </li>

                    <!-- Admin Mobile -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $is_admin ? 'admin/dashboard.php' : 'admin/index.php' ?>">
                            <i class="bi bi-gear me-2"></i>Admin
                        </a>
                    </li>

                    <!-- Cart Mobile -->
                    <li class="nav-item">
                        <a class="nav-link" href="panier.php">
                            <i class="bi bi-cart3 me-2"></i>Panier
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid px-3 px-lg-4">