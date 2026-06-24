<?php
require_once("includes/db.php");
require_once("includes/functions.php");
include("includes/header.php");
?>

<div class="container-lg py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="section-title mb-0">
                <i class="bi bi-cart3 me-2"></i>Votre Panier
            </h2>
        </div>
    </div>

    <div class="row">
        <!-- Panier Items -->
        <div class="col-lg-8">
            <div id="panierContainer" class="panier-container">
                <!-- Les items du panier seront insérés ici par JavaScript -->
            </div>
        </div>

        <!-- Résumé Panier -->
        <div class="col-lg-4">
            <div class="panier-summary">
                <h5 class="mb-3">Résumé de Commande</h5>
                
                <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <strong>Total</strong>
                    <strong id="totalGeneral" style="color: var(--primary); font-size: 1.3rem;">0</strong> FCFA
                </div>

                <a href="commander.php" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-credit-card me-2"></i>Valider la commande
                </a>
                
                <a href="index.php" class="btn btn-outline-primary w-100">
                    <i class="bi bi-arrow-left me-2"></i>Continuer les achats
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.panier-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.panier-item {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    gap: 1.5rem;
    align-items: center;
    transition: var(--transition);
}

.panier-item:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.panier-item-img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    background: var(--secondary);
}

.panier-item-details {
    flex: 1;
}

.panier-item-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.panier-item-price {
    color: var(--primary);
    font-weight: 700;
    font-size: 1.1rem;
}

.panier-item-quantite {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.panier-item-quantite input {
    width: 50px;
    padding: 0.4rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    font-weight: 600;
}

.panier-item-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-remove {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-remove:hover {
    background: #c0392b;
}

.panier-summary {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 100px;
}

.panier-summary h5 {
    font-weight: 700;
    color: var(--text-dark);
}

.summary-item {
    font-size: 0.95rem;
    color: var(--text-light);
}

.summary-total {
    font-size: 1.1rem;
    padding: 1rem 0;
    border-top: 2px solid var(--primary);
}

.empty-cart {
    text-align: center;
    padding: 3rem 1.5rem;
    background: white;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.empty-cart i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-cart h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-cart p {
    color: var(--text-light);
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .panier-item {
        flex-wrap: wrap;
    }

    .panier-item-img {
        width: 80px;
        height: 80px;
        order: 0;
    }

    .panier-summary {
        position: static;
        margin-top: 2rem;
    }

    .panier-item-actions {
        order: 3;
        width: 100%;
        margin-top: 1rem;
    }
}
</style>

<script>
class CartManager {
    constructor() {
        this.cart = JSON.parse(localStorage.getItem("panier")) || [];
        this.render();
    }

    render() {
        const container = document.getElementById("panierContainer");
        
        if (this.cart.length === 0) {
            container.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-basket"></i>
                    <h3>Votre panier est vide</h3>
                    <p>Découvrez nos produits et commencez vos achats!</p>
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-shop me-2"></i>Voir les produits
                    </a>
                </div>
            `;
            return;
        }

        container.innerHTML = this.cart.map((item, index) => `
            <div class="panier-item" data-index="${index}">
                <img src="uploads/${item.image || 'placeholder.jpg'}" alt="${item.nom}" class="panier-item-img">
                
                <div class="panier-item-details">
                    <div class="panier-item-name">${item.nom}</div>
                    <div class="panier-item-price">${item.prix} FCFA</div>
                    
                    <div class="panier-item-quantite">
                        <button onclick="cartManager.decreaseQuantity(${index})" class="btn btn-sm" style="background: #f0f0f0;">-</button>
                        <input type="number" value="${item.quantite}" min="1" onchange="cartManager.updateQuantity(${index}, this.value)">
                        <button onclick="cartManager.increaseQuantity(${index})" class="btn btn-sm" style="background: #f0f0f0;">+</button>
                        <span id="total-${index}" style="font-weight: 600; color: var(--primary);">${item.prix * item.quantite} FCFA</span>
                    </div>
                </div>

                <button class="btn-remove" onclick="cartManager.remove(${index})">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </div>
        `).join('');

        this.updateSummary();
    }

    updateQuantity(index, value) {
        const qty = Math.max(1, parseInt(value) || 1);
        this.cart[index].quantite = qty;
        this.save();
    }

    increaseQuantity(index) {
        this.cart[index].quantite += 1;
        this.save();
    }

    decreaseQuantity(index) {
        if (this.cart[index].quantite > 1) {
            this.cart[index].quantite -= 1;
            this.save();
        }
    }

    remove(index) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet article?')) {
            this.cart.splice(index, 1);
            this.save();
        }
    }

    save() {
        localStorage.setItem("panier", JSON.stringify(this.cart));
        this.render();
    }

    updateSummary() {
        const sousTotal = this.cart.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
        const total = sousTotal;

        // document.getElementById("sousTotal").textContent = sousTotal;

        const sousTotalEl = document.getElementById("sousTotal");
       if (sousTotalEl) sousTotalEl.textContent = sousTotal;


        
        document.getElementById("totalGeneral").textContent = total;
        // Mettre à jour le badge du panier
        const count = this.cart.reduce((sum, item) => sum + item.quantite, 0);
        const badge = document.getElementById("cart-count");
        if (badge) badge.textContent = count;
    }
}

const cartManager = new CartManager();
</script>

<?php include("includes/footer.php"); ?>