<?php
// Function to establish a database connection
function getConnection() {
    include 'Unit6_database_credentials.php';

    // luna ONLY
    error_reporting(E_ALL);
    ini_set('display_errors', True);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to retrieve all customers
function getAllCustomers($conn) {
    $sql = "SELECT * FROM customer";
    return $conn->query($sql);
}

// Function to count the number of customers
function getNumberOfCustomers($conn) {
    $sql = "SELECT COUNT(*) AS count FROM customer";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to find a customer by ID
function findCustomerById($conn, $customerId) {
    $sql = "SELECT * FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to find a customer by email
function findCustomerByEmail($conn, $email) {
    $sql = "SELECT * FROM customer WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to add a customer
function addCustomer($conn, $first_name, $last_name, $email) {
    $sql = "INSERT INTO customer (first_name, last_name, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $first_name, $last_name, $email);
    // echo 'Adding new customer ' . $first_name . ' ' . $last_name;
    return $stmt->execute();
}

// Function to retrieve all orders
function getAllOrders($conn) {
    $sql = "SELECT * FROM orders";
    return $conn->query($sql);
}

// Function to count the number of orders
function getNumberOfOrders($conn) {
    $sql = "SELECT COUNT(*) AS count FROM orders";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to add an order
function addOrder($conn, $time, $product_id, $price, $customer_id, $quantity_purchased, $tax, $donation) {
    $sql = "INSERT INTO orders (time, product_id, price, customer_id, quantity_purchased, tax, donation) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidiidd", $time, $product_id, $price, $customer_id, $quantity_purchased, $tax, $donation);
    // echo 'Adding an order';
    return $stmt->execute();
}

// Function to retrieve all active products
function getAllActiveProducts($conn) {
    $sql = "SELECT * FROM product WHERE inactive = 0";
    return $conn->query($sql);
}

// Function to retrieve all products
function getAllProducts($conn) {
    $sql = "SELECT * FROM product";
    return $conn->query($sql);
}

// Function to find a product by ID
function getProductById($conn, $productId) {
    $sql = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to sell a product
function sellProduct($conn, $productId, $quantityToSell) {
    $product = getProductById($conn, $productId);

    if ($product) {
        $currentQuantity = $product['quantity_in_stock'];

        if ($currentQuantity >= $quantityToSell) {
            $newQuantity = $currentQuantity - $quantityToSell;
        } else {
            $newQuantity = 0; // Set the quantity to 0 if not enough in stock
        }
        
        // Update the product's quantity in stock
        $sql = "UPDATE product SET quantity_in_stock = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newQuantity, $productId);
        
        if ($stmt->execute()) {
            return array(
                'success' => true,
                'productName' => $product['product_name'],
                'newQuantity' => $newQuantity
            ); // Sale successful
        }
    }
    
    return array(
        'success' => false,
        'productName' => null,
        'newQuantity' => 0
    );
}

// Function to get the updated product quantity
function getUpdatedProductQuantity($conn, $productId) {
    $product = getProductById($conn, $productId);
    
    if ($product) {
        return $product['quantity_in_stock'];
    }
    
    return false;
}

// Function to find an order by customer ID, product ID, and timestamp
function findOrder($conn, $customerId, $productId, $timestamp) {
    $sql = "SELECT * FROM orders WHERE customer_id = ? AND product_id = ? AND time = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $customerId, $productId, $timestamp);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to display the quantity in stock of a product.
function getProductQuantity($conn, $productId) {
    $sql = "SELECT quantity_in_stock FROM product WHERE product_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($quantity);
        $stmt->fetch();
        $stmt->close();
        return $quantity;
    }
    
    return 0;
}

function getCustomerSuggestions($conn, $input, $searchBy) {
    $columnToSearch = ($searchBy === 'last') ? 'last_name' : 'first_name';

    $sql = "SELECT * FROM customer WHERE $columnToSearch LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = array();
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    return $suggestions;
}

function createProductTable($conn) {
    $products = getAllProducts($conn);

    if ($products && $products->num_rows > 0) {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th>Image</th>';
        echo '<th>Quantity</th>';
        echo '<th>Price</th>';
        echo '<th>Inactive</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($product = $products->fetch_assoc()) {
            echo '<tr data-product-id="' . $product['product_id'] . '">';
            echo '<td>' . $product['product_name'] . '</td>';
            echo '<td>' . $product['image_name'] . '</td>';
            echo '<td>' . $product['quantity_in_stock'] . '</td>';
            echo '<td>' . $product['price'] . '</td>';
            echo '<td>' . ($product['inactive'] == 1 ? 'Yes' : 'No') . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No products available.</p>';
    }
}

// Function to add a product
function addProduct($conn, $productName, $imageName, $quantity, $price, $inactive) {
    $sql = "INSERT INTO product (product_name, image_name, quantity_in_stock, price, inactive) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiid", $productName, $imageName, $quantity, $price, $inactive);
    return $stmt->execute();
}

// Function to update a product
function updateProduct($conn, $productId, $productName, $imageName, $quantity, $price, $inactive) {
    $sql = "UPDATE product SET product_name = ?, image_name = ?, quantity_in_stock = ?, price = ?, inactive = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiidi", $productName, $imageName, $quantity, $price, $inactive, $productId);
    return $stmt->execute();
}

// Function to delete a product
function deleteProduct($conn, $productId) {
    // Check for existing orders
    $sqlCheckOrders = "SELECT COUNT(*) AS orderCount FROM orders WHERE product_id = ?";
    $stmtCheckOrders = $conn->prepare($sqlCheckOrders);
    $stmtCheckOrders->bind_param("i", $productId);
    $stmtCheckOrders->execute();
    $resultCheckOrders = $stmtCheckOrders->get_result();
    $orderCount = $resultCheckOrders->fetch_assoc()['orderCount'];

    if ($orderCount > 0) {
        return array(
            'success' => false,
            'message' => 'Cannot delete the product. There are existing orders.'
        );
    }

    // If no orders, proceed with deletion
    $sqlDeleteProduct = "DELETE FROM product WHERE product_id = ?";
    $stmtDeleteProduct = $conn->prepare($sqlDeleteProduct);
    $stmtDeleteProduct->bind_param("i", $productId);
    
    if ($stmtDeleteProduct->execute()) {
        return array(
            'success' => true,
            'message' => 'Product deleted successfully.'
        );
    } else {
        return array(
            'success' => false,
            'message' => 'Error deleting the product.'
        );
    }
}

// Function to get user for login
function getUserByEmailAndPassword($conn, $email, $password) {
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}
?>