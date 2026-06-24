<?php
require_once("includes/db.php");
require_once("includes/functions.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Requête invalide.");
    }

    $nom = clean_input($_POST['nom']);
    $telephone = clean_input($_POST['telephone']);
    $adresse = isset($_POST['adresse']) ? clean_input($_POST['adresse']) : '';
    $produits = $_POST['produits'];
    $total = $_POST['total'];

    $stmt = $pdo->prepare("INSERT INTO commandes (nom_client,telephone,adresse,produits,total) VALUES (?,?,?,?,?)");
    $stmt->execute([$nom,$telephone,$adresse,$produits,$total]);

    $numero = "221783783058";
    $message = urlencode("Bonjour, je souhaite commander : \n$produits\nTotal : $total FCFA\nNom: $nom\nTéléphone: $telephone\nAdresse: $adresse");


    echo "<script>localStorage.removeItem('panier');</script>";
    header("Location: https://wa.me/$numero?text=$message");
    exit();


    
}

include("includes/header.php");

// Récupérer les items du panier pour vérification
$panier = json_decode($_COOKIE['panier'] ?? '[]', true);
?>

<div class="container-lg py-4">
    <h2 class="section-title mb-4">
        <i class="bi bi-credit-card me-2"></i>Finaliser votre Commande
    </h2>

    <div class="row">
        <!-- Formulaire -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-right">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-person-check me-2"></i>Vos Informations
                    </h5>

                    <form method="POST" onsubmit="preparerCommande()" id="commandeForm">
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                        <input type="hidden" name="produits" id="produitsInput">
                        <input type="hidden" name="total" id="totalInput">

                        <div class="mb-3">
                            <label class="form-label fw-600">
                                <i class="bi bi-person me-2"></i>Nom complet *
                            </label>
                            <input type="text" name="nom" class="form-control" placeholder="Entrez votre nom complet" required>
                        </div>



                        <div class="mb-3">
                            <label class="form-label fw-600">
                                <i class="bi bi-geo-alt me-2"></i>Adresse *
                            </label>
                            <input type="text" name="adresse" class="form-control" placeholder="Entrez votre adresse" required>
                        </div>




                        <div class="mb-3">
                            <label class="form-label fw-600">
                                <i class="bi bi-telephone me-2"></i>Téléphone WhatsApp *
                            </label>
                            <input type="tel" name="telephone" class="form-control" placeholder="Ex: +212 XXX-XXXXXX" pattern="[0-9+\s-]+" required>
                            <small class="text-muted">Nous utiliserons ce numéro pour confirmer votre commande via WhatsApp</small>
                        </div>


                        <label>
<input type="checkbox" id="livraison">
Paiement à la livraison (+2000 FCFA)
</label>

                        <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
<i class="bi bi-whatsapp me-2"></i>Commander via WhatsApp
</button>

<a id="payerWave" target="_blank" class="btn btn-lg w-100 mb-2" style="background:#1DA1F2;color:white;">
    <img src="assets/images/wave.jpeg" width="25" style="margin-right:8px;" alt="">
 Payer avec Wave
</a>

<!-- <a href="tel:#144#" class="btn btn-warning btn-lg w-100 mb-2" style="backround:#ff7900;color:white;">
📱 Payer avec Orange Money
</a>  -->

<h4 type="" class="btn btn-dark btn-lg w-100">
🚚  Paiement à la livraison
</button>







                    </form>
                </div>
            </div>
        </div>

        <!-- Résumé Commande -->
        <div class="col-lg-4" data-aos="fade-left">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-receipt me-2"></i>Résumé de la Commande
                    </h5>

                    <div id="resumeCommande">
                        <!-- Les items seront insérés par JS -->
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong id="totalAffiche" style="color: var(--primary); font-size: 1.2rem;">0 FCFA</strong>
                    </div>

                    <div>
Livraison : <span id="livraisonPrix">0 FCFA</span>
</div>

                    <hr>

                    <a href="panier.php" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                        <i class="bi bi-pencil me-1"></i>Modifier le panier
                    </a>

                    <a href="index.php" class="btn btn-secondary btn-sm w-100">
                        <i class="bi bi-arrow-left me-1"></i>Continuer shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-title {
    color: var(--text-dark);
}

.fw-600 {
    font-weight: 600;
}

.form-control, .form-label {
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(152, 35, 32, 0.1);
}

.resume-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.95rem;
}

.resume-item:last-child {
    border-bottom: none;
}

.resume-qty {
    color: var(--text-light);
    font-size: 0.9rem;
}
</style>

<script>
//livraison
document.getElementById("livraison").onclick = function(){

let panier = JSON.parse(localStorage.getItem("panier")) || [];

let total = 0;

panier.forEach(function(item){
total += item.prix * item.quantite;
});

if(this.checked){
total = total + 2000;
}

document.getElementById("totalAffiche").textContent = total + " FCFA";

};


//calcule frais livraison
document.getElementById("livraison").addEventListener("change", function(){

let livraison = 0;

if(this.checked){
livraison = 2000;
}

document.getElementById("livraisonPrix").innerText = livraison + " FCAF";

});





//paiment wave


document.getElementById("payerWave").onclick = function(){

let panier = JSON.parse(localStorage.getItem("panier")) || [];

let total = 0;

panier.forEach(function(item){
total = total + (item.prix * item.quantite);
});


if(document.getElementById("livraison").checked){
total += 2000;
}

window.location.href =
"https://pay.wave.com/m/221783783058/?amount=" + total;

};
//fin paiement wave


    

function preparerCommande() {
    let panier = JSON.parse(localStorage.getItem("panier")) || [];
    
    if (panier.length === 0) {
        alert("Votre panier est vide!");
        return false;
    }

    let produitsText = "";
    let total = 0;

    panier.forEach(p => {
        produitsText += ${p.nom} x${p.quantite} - ${p.prix*p.quantite} FCFA\n;
        total += p.prix * p.quantite;
    });

    if(document.getElementById("livraison").checked){
total = total + 2000;
}


    document.getElementById("produitsInput").value = produitsText;
    document.getElementById("totalInput").value = total;
//wave paiment
    document.getElementById("payerWave").href =
"https://pay.wave.com/m/221783783058/?amount=" + total;

    // Bloquer la soumission normale (laisser WhatsApp gérer)
    return true;
}

// Afficher le résumé du panier au chargement
document.addEventListener('DOMContentLoaded', function() {
    let panier = JSON.parse(localStorage.getItem("panier")) || [];
    let html = '';
    let total = 0;

    if (panier.length === 0) {
        html = '<div class="alert alert-warning">Votre panier est actuellement vide</div>';
    } else {
        panier.forEach(item => {
            const sousTotal = item.prix * item.quantite;
            total += sousTotal;
            html += `
                <div class="resume-item">
                    <div>
                        <div>${item.nom}</div>
                        <span class="resume-qty">x${item.quantite} × ${item.prix} FCFA</span>
                    </div>
                    <div>${sousTotal} FCFA</div>
                </div>
            `;
        });
    }

    document.getElementById('resumeCommande').innerHTML = html;
    document.getElementById('totalAffiche').textContent = total + ' FCFA';
});
</script>

<?php include("includes/footer.php"); ?>