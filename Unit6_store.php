<?php include('Unit6_header.php'); ?>
<link rel="stylesheet" href="Unit6_store.css">
<?php include('Unit6_database.php') ?>
<?php date_default_timezone_set("America/Denver"); ?>

<main>
    <form action="Unit6_process_order.php" method="post">
        <div id="product-info-container">
            <div id="product-image-container"></div>
            <div id="quantity-message"></div>
        </div>
        <fieldset>
            <legend>Personal Info</legend>
            <label for="first_name">First Name: *</label>
            <input type="text" id="first_name" name="first_name" required pattern="[A-Za-z ']+" title="Only letters, spaces, and apostrophes are allowed">
            <label for="last_name">Last Name: *</label>
            <input type="text" id="last_name" name="last_name" required pattern="[A-Za-z ']+" title="Only letters, spaces, and apostrophes are allowed">
            <label for="email">Email: *</label>
            <input type="email" id="email" name="email" required>
            <input type="hidden" name="timestamp" value="<?php echo time(); ?>">

        </fieldset>
        <fieldset>
            <legend>Product Info</legend>
            <select id="product" name="product" required>
                <option value="" disabled selected hidden>- Select a LEGO Set -</option>
                <?php
                $products = getAllActiveProducts(getConnection());

                while ($row = $products->fetch_assoc()) {
                    $productId = $row['product_id'];
                    $productName = $row['product_name'];
                    $price = $row['price'];
                    $imageName = $row['image_name'];
                    $inStock = $row['quantity_in_stock'];

                    echo "<option value='$productId' data-image='$imageName' data-quantity='$inStock'>$productName - $$price</option>";
                }
                ?>
            </select>
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
        </fieldset>
        <fieldset>
            <legend>Donation</legend>
            <label>Round up to the nearest dollar for a donation?</label>
            <input type="radio" id="donation_yes" name="donation" value="yes">
            <label for="donation_yes">Yes</label>
            <input type="radio" id="donation_no" name="donation" value="no" checked>
            <label for="donation_no">No</label>
        </fieldset>
        <input type="submit" name="purchase" value="Purchase">
    </form>
</main>

<script>
    const productDropdown = document.getElementById("product");
    const productImageContainer = document.getElementById("product-image-container");
    const quantityMessage = document.getElementById("quantity-message");

    productDropdown.addEventListener("change", function () {
        
        const selectedOption = productDropdown.options[productDropdown.selectedIndex];
        const imageURL = selectedOption.getAttribute("data-image");
        const inStock = selectedOption.getAttribute("data-quantity");

        if (inStock === "0") {
            quantityMessage.textContent = "SOLD OUT";
        } else if (inStock < 5) {
            quantityMessage.textContent = `Only ${inStock} left`;
        } else {
            quantityMessage.textContent = "";
        }

        if (imageURL) {
            productImageContainer.innerHTML = `<img src="images/${imageURL}" alt="Product Image">`;
        } else {
            productImageContainer.innerHTML = "";
        }
    });
</script>

<?php include('Unit6_footer.php'); ?>