<?php
include('Unit6_database.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Call the function to get the quantity available from the database
    $quantity_available = getProductQuantity(getConnection(), $product_id);

    // Return the quantity text
    echo $quantity_available;
}
?>
