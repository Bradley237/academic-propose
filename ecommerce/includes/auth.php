<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../pages/login.php");
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../pages/login.php");
        exit();
    }
}

function getCartCount($conn, $user_id) {
    $result = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}
?>
