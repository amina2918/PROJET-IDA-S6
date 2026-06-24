<?php

// =======================
// Sécuriser affichage (XSS)
// =======================
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// =======================
// Nettoyer données POST
// =======================
function clean_input($data) {
    return trim(strip_tags($data));
}

// =======================
// Générer Token CSRF
// =======================
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// =======================
// Vérifier Token CSRF
// =======================
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// =======================
// Vérifier admin connecté
// =======================
function check_admin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: index.php");
        exit();
    }
}

// =======================
// Redirection sécurisée
// =======================
function redirect($url) {
    header("Location: $url");
    exit();
}

// =======================
// Formater prix
// =======================
function format_price($price) {
    return number_format($price, 0, ',', ' ') . " FCA";
}

// =======================
// Upload image sécurisé
// =======================
function upload_image($file) {

    if ($file['error'] !== 0) {
        return false;
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }

    if ($file['size'] > $max_size) {
        return false;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = uniqid() . '.' . $extension;

    move_uploaded_file($file['tmp_name'], "../uploads/" . $new_name);

    return $new_name;
}