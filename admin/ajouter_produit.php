<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");
check_admin();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Requête invalide.");
    }

    $nom = clean_input($_POST['nom']);
    $categorie = $_POST['categorie'];
    $description = clean_input($_POST['description']);
    $prix = $_POST['prix'];
    
    $image = upload_image($_FILES['image']);

    $stmt = $pdo->prepare("INSERT INTO produits (nom,categorie_id,description,prix,image) VALUES (?,?,?,?,?)");
    $stmt->execute([$nom,$categorie,$description,$prix,$image]);



    redirect("dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ajouter Produit</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("nav_admin.php"); ?>

<div class="container mt-4">
<h3>Ajouter Produit</h3>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

<div class="mb-3">
<label>Nom</label>
<input type="text" name="nom" class="form-control" required>
</div>

<div class="mb-3">
<label>Catégorie</label>
<select name="categorie" class="form-control">
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id'] ?>"><?= e($cat['nom']) ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Prix</label>
<input type="number" name="prix" class="form-control" required>
</div>






<div class="mb-3">
<label>Image</label>
<input type="file" name="image" class="form-control" required>
</div>

<button class="btn btn-danger">Ajouter</button>
</form>
</div>
</body>
</html>