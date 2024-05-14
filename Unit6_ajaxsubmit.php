<?php
include 'Unit6_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $quantityToPurchase = $_POST['quantityToPurchase'];
    $customerFirstName = $_POST['customerFirstName'];
    $customerLastName = $_POST['customerLastName'];
    $customerEmail = $_POST['customerEmail'];

    $conn = getConnection();

    $product = getProductById($conn, $productId);

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    $quantityAvailable = $product['quantity_in_stock'];

    if ($quantityToPurchase > $quantityAvailable) {
        echo "Quantity to purchase exceeds available quantity.";
        exit;
    }

    $existingCustomer = findCustomerByNameAndEmail($conn, $customerFirstName, $customerLastName, $customerEmail);

    if (!$existingCustomer) {
        addCustomer($conn, $customerFirstName, $customerLastName, $customerEmail);
        $existingCustomer = findCustomerByNameAndEmail($conn, $customerFirstName, $customerLastName, $customerEmail);
    }

    $orderTotal = $product['price'] * $quantityToPurchase;

    $timestamp = date('Y-m-d H:i:s');
    $customerId = $existingCustomer['customer_id'];

    if (addOrder($conn, $timestamp, $productId, $product['price'], $customerId, $quantityToPurchase, 0, 0)) {
        echo "Order placed successfully. Total: $" . number_format($orderTotal, 2);
    } else {
        echo "Failed to place the order.";
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>