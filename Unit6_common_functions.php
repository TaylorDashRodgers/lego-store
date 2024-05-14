<?php
function checkUserRole($requiredRole) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['role'])) {
        header("Location: Unit6_index.php?err=Must log in first");
        exit();
    }

    $userRole = intval($_SESSION['role']);

    if ($userRole < $requiredRole) {
        header("Location: Unit6_index.php?err=You are not authorized for that page!");
        exit();
    }
}
?>