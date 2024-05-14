<?php
include('Unit6_database.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = getUserByEmailAndPassword(getConnection(), $email, $password);

        if ($user) {
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
        
            if ($user['role'] == 1) {
                header("Location: Unit6_order_entry.php");
            } elseif ($user['role'] == 2) {
                header("Location: Unit6_adminProduct.php");
            }
        } else {
            header("Location: Unit6_index.php?err=Invalid User");
        }
    }
}
?>