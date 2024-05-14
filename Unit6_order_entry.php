<?php session_start(); ?>
<?php include('Unit6_header.php'); ?>
<link rel="stylesheet" href="Unit6_store.css">
<?php include('Unit6_database.php'); ?>
<?php include('Unit6_common_functions.php'); ?>
<?php date_default_timezone_set("America/Denver"); ?>

<?php checkUserRole(1); ?>

<main>
    <div id="left">
        <form action="Unit6_process_order.php" method="post">
            <fieldset id="leftField">
                <legend>Personal Info</legend>
                <label for="first_name">First Name: *</label>
                <input type="text" id="first_name" name="first_name" required pattern="[A-Za-z ']+" title="Only letters, spaces, and apostrophes are allowed">
                <label for="last_name">Last Name: *</label>
                <input type="text" id="last_name" name="last_name" required pattern="[A-Za-z ']+" title="Only letters, spaces, and apostrophes are allowed">
                <label for="email">Email: *</label>
                <input type="email" id="email" name="email" required>
                <input type="hidden" name="timestamp" value="<?php echo time(); ?>">
            </fieldset>
            <fieldset id="leftField">
                <legend>Product Info</legend>
                <select id="product" name="product" required onchange="showAvailable()">
                    <option value="" disabled selected hidden>- Select a LEGO Set -</option>
                    <?php
                    $products = getAllProducts(getConnection());

                    while ($row = $products->fetch_assoc()) {
                        $productId = $row['product_id'];
                        $productName = $row['product_name'];
                        $price = $row['price'];
                        $inStock = $row['quantity_in_stock'];

                        echo "<option value='$productId' data-quantity='$inStock'>$productName - $$price</option>";
                    }
                    ?>
                </select>
                <label for="quantityAvailable">Available</label>
                <input type="text" id="quantityAvailable" name="quantityAvailable" readonly>
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1">
            </fieldset>
            <input type="submit" name="purchase" value="Purchase">
            <input type="reset" value="Clear" style="color: red;">
        </form>
    </div>
    <div id="right">
        <table id="customerTable">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
        </table>
    </div>
</main>

<script>

    function showCustomerSuggestions(inputField, searchBy) {
        var input = inputField.value;
        var customerTable = document.getElementById("customerTable");

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "Unit6_get_customer_table.php?input=" + input + "&searchBy=" + searchBy, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                while (customerTable.rows.length > 1) {
                    customerTable.deleteRow(1);
                }

                if (response.message) {
                    var row = customerTable.insertRow(1);
                    var cell = row.insertCell(0);
                    cell.innerHTML = response.message;
                } else {
                    response.forEach(function(customer) {
                        var row = customerTable.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);

                        cell1.innerHTML = customer.first_name;
                        cell2.innerHTML = customer.last_name;
                        cell3.innerHTML = customer.email;
                    });
                }
            }
        };

        xhr.send();
    }

    function handleRowClick(event) {
        if (event.target.tagName === "TD" && event.target.parentElement.tagName === "TR") {

            var selectedRow = event.target.parentElement;

            selectedRow.classList.add("selected-row");

            var rows = customerTable.querySelectorAll("tr");
            rows.forEach(function(row) {
                if (row !== selectedRow) {
                    row.classList.remove("selected-row");
                }
            });

            var firstName = selectedRow.cells[0].innerHTML;
            var lastName = selectedRow.cells[1].innerHTML;
            var email = selectedRow.cells[2].innerHTML;

            document.getElementById("first_name").value = firstName;
            document.getElementById("last_name").value = lastName;
            document.getElementById("email").value = email;
        }
    }

    var customerTable = document.getElementById("customerTable");
    customerTable.addEventListener("click", handleRowClick);


    document.getElementById("first_name").addEventListener("keyup", function() {
        showCustomerSuggestions(this, "first");
    });

    document.getElementById("last_name").addEventListener("keyup", function() {
        showCustomerSuggestions(this, "last");
    });


    function showAvailable() {
        console.log("showAvailable function called");
        var productId = document.getElementById("product").value;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "Unit6_get_quantity.php?product_id=" + productId, true);

        xhr.onreadystatechange = function() {
            console.log("ReadyState: " + xhr.readyState + ", Status: " + xhr.status);
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log("Response: " + xhr.responseText);
                document.getElementById("quantityAvailable").value = xhr.responseText;
            }
        };
        xhr.send();
    }

    document.getElementById("product").addEventListener("change", showAvailable);

</script>

<?php include('Unit6_footer.php'); ?>

<script src="Unit6_script.js"></script>

<?php session_write_close() ?>