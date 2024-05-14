<?php
include('Unit6_header.php');
include('Unit6_database.php');
include('Unit6_common_functions.php');

session_start();

checkUserRole(1);

date_default_timezone_set("America/Denver");

$conn = getConnection();

// Retrieve and display customers
$customers = getAllCustomers($conn);

if ($customers->num_rows > 0) {
    echo '<h2>Customers</h2>';
    echo '<table>';
    echo '<tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>';
    while ($customer = $customers->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $customer['first_name'] . '</td>';
        echo '<td>' . $customer['last_name'] . '</td>';
        echo '<td>' . $customer['email'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<h2>Customers</h2>';
    echo '<p>No customers yet!</p>';
}

// Retrieve and display orders
$orders = getAllOrders($conn);

if ($orders->num_rows > 0) {
    echo '<h2>Orders</h2>';
    echo '<table>';
    echo '<tr><th>Customer</th><th>Lego Set</th><th>Date</th><th>Quantity</th><th>Price</th><th>Tax</th><th>Donation</th><th>Total</th></tr>';
    while ($order = $orders->fetch_assoc()) {
        $customer = findCustomerById($conn, $order['customer_id']);
        $product = getProductById($conn, $order['product_id']);

        echo '<tr>';
        echo '<td>' . $customer['first_name'] . ' ' . $customer['last_name'] . '</td>';
        echo '<td>' . $product['product_name'] . '</td>';
        echo '<td>' . date('Y-m-d H:i:s', $order['time']) . '</td>';
        echo '<td>' . $order['quantity_purchased'] . '</td>';
        echo '<td>$' . number_format($order['price'], 2) . '</td>';
        echo '<td>$' . number_format($order['tax'], 2) . '</td>';
        echo '<td>$' . number_format($order['donation'], 2) . '</td>';
        $total = $order['quantity_purchased'] * $order['price'] + $order['tax'] + $order['donation'];
        echo '<td>$' . $total . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<h2>Orders</h2>';
    echo '<p>No orders yet!</p>';
}

// Retrieve and display products
$products = getAllProducts($conn);

if ($products->num_rows > 0) {
    echo '<h2>Products</h2>';
    echo '<table>';
    echo '<tr><th>Lego Set</th><th>Quantity in Stock</th><th>Price</th></tr>';
    while ($product = $products->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $product['product_name'] . '</td>';
        echo '<td>' . $product['quantity_in_stock'] . '</td>';
        echo '<td>$' . number_format($product['price'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<h2>Products</h2>';
    echo '<p>No products yet!</p>';
}

include('Unit6_footer.php');
?>