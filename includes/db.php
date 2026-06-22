<?php
// Sécurité des erreurs (désactivé en production)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Paramètres DB
$host = "localhost";
$dbname = "E-tech-boutique";
$username = "root";
$password = "";

// Connexion PDO sécurisée
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// =======================
// Sécurité des sessions
// =======================
if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false, // Mettre TRUE en HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    session_start();
}

// Régénération ID session
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}