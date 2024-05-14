<?php session_start(); ?>
<?php include('Unit6_header.php'); ?>
<?php include 'Unit6_database.php'; ?>
<?php include('Unit6_common_functions.php'); ?>

<?php session_start(); ?>
<?php checkUserRole(1); ?>

<div class="container">
    <div class="product-list">
        <span id="productTable">
            <?php createProductTable(getConnection()); ?>
        </span>
    </div>
    <div id="data-entry-form">
        <h2>Puzzle Info</h2>
        <form id="productForm" method="post" action="">
            <label for="productName">Lego Name: *</label>
            <input type="text" id="productName" name="productName" required pattern="[A-Za-z ']+" title="Only letters, spaces, and apostrophes are allowed" required>

            <label for="imageName">Image Name: *</label>
            <input type="text" id="imageName" name="imageName" required pattern="[A-Za-z ']+" title="Only letters, spaces, and apostrophes are allowed" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="" min="0">

            <label for="price">Price: *</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="inactive">Make Inactive:</label>
            <input type="checkbox" id="inactive" name="inactive">

            <input type="hidden" id="productId" name="productId" value="">
            <button type="button" onclick="addProduct()" id="otherButtons">Add</button>
            <button type="button" onclick="updateProduct()" id="otherButtons">Update</button>
            <button type="button" onclick="deleteProduct()" id="deleteButton">Delete</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function addProduct() {
        $.ajax({
            type: "POST",
            url: "Unit6_adminProduct_ajax.php",
            data: $('#productForm').serialize() + '&action=add',
            success: function(response) {
                alert(response);
                $('#productTable').load('Unit6_adminProduct.php #productTable');
            }
        });
    }

    function updateProduct() {
        var productId = document.getElementById("productId").value;
        $.ajax({
            type: "POST",
            url: "Unit6_adminProduct_ajax.php",
            data: $('#productForm').serialize() + '&action=update',
            success: function(response) {
                alert(response);
                $('#productTable').load('Unit6_adminProduct.php #productTable');
            }
        });
    }

    function deleteProduct() {
        var productId = document.getElementById("productId").value;
        var confirmation = confirm("Are you sure you want to delete this product?");
        if (confirmation) {
            $.ajax({
                type: "POST",
                url: "Unit6_adminProduct_ajax.php",
                data: $('#productForm').serialize() + '&action=delete',
                success: function(response) {
                    alert(response);
                    $('#productTable').load('Unit6_adminProduct.php #productTable');
                }
            });
            loadProductTable();
            clearForm();
        }
    }

    function handleRowClick(event) {
        if (event.target.tagName === "TD" && event.target.parentElement.tagName === "TR") {
            var selectedRow = event.target.parentElement;

            selectedRow.classList.add("selected-row");

            var rows = document.getElementById("productTable").querySelectorAll("tr");
            rows.forEach(function(row) {
                if (row !== selectedRow) {
                    row.classList.remove("selected-row");
                }
            });

            var productId = selectedRow.getAttribute("data-product-id");
            var productName = selectedRow.cells[0].innerHTML;
            var imageName = selectedRow.cells[1].innerHTML;
            var quantity = selectedRow.cells[2].innerHTML;
            var price = selectedRow.cells[3].innerHTML;
            var inactive = selectedRow.cells[4].innerHTML.trim().toLowerCase() === 'yes';

            document.getElementById("productId").value = productId;
            document.getElementById("productName").value = productName;
            document.getElementById("imageName").value = imageName;
            document.getElementById("quantity").value = quantity;
            document.getElementById("price").value = price;

            document.getElementById("inactive").checked = inactive;
        }
    }

    var productTable = document.getElementById("productTable");
    productTable.addEventListener("click", handleRowClick);
</script>

<?php include('Unit6_footer.php'); ?>

<?php session_write_close() ?>