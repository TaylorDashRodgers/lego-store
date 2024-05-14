<!DOCTYPE html>
<html>
<head>
    <title>Your LEGO Store</title>
    <link rel="stylesheet" href="Unit6_common.css">
    <link rel="stylesheet" href="Unit6_process_order.css">
    <link rel="stylesheet" href="Unit6_admin.css">
    <link rel="stylesheet" href="Unit6_adminProduct.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php
                if (isset($_SESSION['role'])) {
                    echo '<li><a href="Unit6_index.php">Home</a></li>';
                    echo '<li><a href="Unit6_store.php">Store</a></li>';

                    if ($_SESSION['role'] == 1) {
                        echo '<li><a href="Unit6_order_entry.php">Order Entry</a></li>';
                        echo '<li><a href="Unit6_admin.php">Admin</a></li>';
                    } elseif ($_SESSION['role'] == 2) {
                        echo '<li><a href="Unit6_order_entry.php">Order Entry</a></li>';
                        echo '<li><a href="Unit6_adminProduct.php">Products</a></li>';
                        echo '<li><a href="Unit6_admin.php">Admin</a></li>';
                    }

                    echo "<li style='float:right'><a href='Unit6_logout.php'>Logout</a></li>";

                    if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
                        echo "<p id='welcome-message'>Welcome, {$_SESSION['first_name']}!</p>";
                    }
                } else {
                    echo '<li><a href="Unit6_index.php">Home</a></li>';
                    echo '<li><a href="Unit6_store.php">Store</a></li>';
                }
                ?>
            </ul>
        </nav>
        <h1>The Lego and Game Store</h1>
    </header>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="Unit6_script.js"></script>