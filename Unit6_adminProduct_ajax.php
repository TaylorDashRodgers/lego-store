<?php
include 'Unit6_database.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

$conn = getConnection();

switch ($action) {
    case 'add':
        handleAddProduct($conn);
        break;
    case 'update':
        handleUpdateProduct($conn);
        break;
    case 'delete':
        handleDeleteProduct($conn);
        break;
    default:
        break;
}

// Function to handle adding a product
function handleAddProduct($conn) {
    $productName = $_POST['productName'];
    $imageName = $_POST['imageName'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $price = $_POST['price'];
    $inactive = isset($_POST['inactive']) ? 1 : 0;

    $result = addProduct($conn, $productName, $imageName, $quantity, $price, $inactive);

    echo $result ? 'Product added successfully.' : 'Error adding the product.';
}

// Function to handle updating a product
function handleUpdateProduct($conn) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $imageName = $_POST['imageName'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $price = $_POST['price'];
    $inactive = isset($_POST['inactive']) ? 1 : 0;

    $result = updateProduct($conn, $productId, $productName, $imageName, $quantity, $price, $inactive);

    echo $result ? 'Product updated successfully.' : 'Error updating the product.';
}

// Function to handle deleting a product
function handleDeleteProduct($conn) {
    $productId = $_POST['productId'];

    $result = deleteProduct($conn, $productId);

    echo $result['success'] ? $result['message'] : 'Error deleting the product.';
}
?>