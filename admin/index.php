<?php
require_once("../includes/db.php");
require_once("../includes/functions.php");

if (isset($_SESSION['admin_id'])) {
    redirect("dashboard.php");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Requête invalide.");
    }

    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        redirect("dashboard.php");
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - E-Tech-Boutique</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin_style.css">
</head>
<body class="admin-bg">

<div class="login-container">
    <div class="login-card shadow-lg">
        <h3 class="text-center mb-4">E-Tech-Boutique</h3>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

            <div class="mb-3">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-admin w-100">Se connecter</button>
        </form>
    </div>
</div>

</body>
</html>