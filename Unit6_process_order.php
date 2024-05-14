<?php 
include('Unit6_header.php');
include('Unit6_database.php');

date_default_timezone_set("America/Denver");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $email = $_POST["email"];
    $productId = $_POST["product"];
    $timestamp = $_POST["timestamp"];
    $quantity = intval($_POST["quantity"]);
    if($_POST["donation"] == "yes"){
        $donate = true;   
    } else {
        $donate = false;
    }

    $conn = getConnection();
    $existingCustomer = findCustomerByEmail($conn, $email);

    if ($existingCustomer) {
        echo "<p id='process_order'><b>Hello $firstName $lastName</b> - Welcome back!</p>";
        $customer_id = $existingCustomer['customer_id'];
    } else {
        $customerAdded = addCustomer($conn, $firstName, $lastName, $email);
        $existingCustomer = findCustomerByEmail($conn, $email);
        $customer_id = $existingCustomer['customer_id'];
        if ($customerAdded) {
            echo "<p id='process_order'><b>Hello $firstName $lastName</b> - Thank you for becoming a customer!</p>";
        } else {
            echo "<p id='process_order'>Error: Failed to add customer.</p>";
        }
    }

    $product = getProductById($conn, $productId);
    $productName = $product['product_name'];
    $price = $product['price'];

    $subtotal = $quantity * $price;

    $taxRate = 0.08;
    $tax = $subtotal * $taxRate;

    $totalWithTax = $subtotal + $tax;

    if ($donate) {
        $totalWithDonation = ceil($totalWithTax);
    } else {
        $totalWithDonation = $totalWithTax;
    }

    echo "<p id='process_order'>We hope you enjoy your <b>$productName</b> Lego Set!</p>";
    echo "<p id='process_order'>Order details:</p>";

    echo "<p id='process_order'>$quantity @ $".number_format($price, 2).":    $".number_format($subtotal, 2)."</p>";
    echo "<p id='process_order'>Tax:    $".number_format($tax, 2)."</p>";
    echo "<p id='process_order'>Total:    $".number_format($totalWithTax, 2)."</p>";

    if ($donate) {
        echo "<p id='process_order'>Total with donation: $".number_format($totalWithDonation, 2)."</p>";
    }

    $existingOrder = findOrder($conn, $existingCustomer['customer_id'], $productId, $timestamp);

    if ($existingOrder) {
        echo "<p>Duplicate order detected. No new order inserted.</p>";
    } else {
        $time = time();
        $product_id = $productId;
        $price = $price;
        $quantity_purchased = $quantity;
        $tax = $tax;
        $donation = $donate ? 1 : 0;

        $orderAdded = addOrder($conn, $time, $product_id, $price, $customer_id, $quantity_purchased, $tax, $donation);

        if ($orderAdded) {
            echo "<p id='process_order'>We'll send special offers to $email</p>";
        } else {
            echo "<p>Error: Failed to add the order.</p>";
        }
    }

    if (isset($_COOKIE["viewedItems"])) {
        $viewedItems = json_decode($_COOKIE["viewedItems"]);

        if (!empty($viewedItems)) {
            if (count($viewedItems) > 1){
                echo "<p id='viewing_deal'>Based on your viewing history, we'd like to offer 20% off these items:</p>";
                echo "<ul id='viewing_deal'>";
                foreach ($viewedItems as $viewedItemId) {
                    $viewedProduct = getProductById($conn, $viewedItemId);
                    echo "<li>{$viewedProduct['product_name']}</li>";
                }
                echo "</ul>";

                setcookie("viewedItems", "[]", time() - 3600, "/");
            }
        }
    }

    $quantityToSell = $quantity;

    $productUpdateResult = sellProduct($conn, $product_id, $quantityToSell);

    if ($productUpdateResult['success']) {
        $newQuantity = $productUpdateResult['newQuantity'];
        // echo "<p>Quantity in stock for $productName: $newQuantity</p>";
    } else {
        echo "<p>Error: Failed to update the product's quantity.</p>";
    }

} else {
    echo "<p>Error: Form not submitted.</p>";
}

include('Unit6_footer.php');
?>