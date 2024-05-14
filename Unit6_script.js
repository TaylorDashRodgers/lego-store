$(document).ready(function() {
    $("#submit").click(function(e) {
        e.preventDefault();

        var productId = $("#product").val();
        var quantityToPurchase = parseInt($("#quantity").val());
        var customerFirstName = $("#first_name").val();
        var customerLastName = $("#last_name").val();
        var customerEmail = $("#email").val();

        if (productId === '' || quantityToPurchase <= 0 || customerFirstName === '' || customerLastName === '' || customerEmail === '') {
            alert("Please fill in all required fields.");
            return;
        }

        var quantityAvailable = parseInt($("#quantityAvailable").val());
        if (quantityToPurchase > quantityAvailable) {
            alert("Quantity to purchase exceeds available quantity.");
            return;
        }

        $.ajax({
            type: "POST",
            url: "Unit6_ajaxsubmit.php",
            data: {
                productId: productId,
                quantityToPurchase: quantityToPurchase,
                customerFirstName: customerFirstName,
                customerLastName: customerLastName,
                customerEmail: customerEmail
            },
            cache: false,
            success: function(response) {
                alert(response);
                clearForm();
                clearCustomerTable();
            }
        });
    });

    function clearForm() {
        $("#product").val('');
        $("#quantity").val(1);
        $("#first_name").val('');
        $("#last_name").val('');
        $("#email").val('');
    }

    function clearCustomerTable() {
        $("#customerSuggestions").empty();
    }

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    productDropdown.addEventListener("change", function () {
        const selectedOption = productDropdown.options[productDropdown.selectedIndex];
        const productId = selectedOption.value;

        // Get the viewed items from the cookie
        var viewedItems = getCookie("viewedItems") ? JSON.parse(getCookie("viewedItems")) : [];

        // Check if the product is already in viewed items
        if (!viewedItems.includes(productId)) {
            // Add the product to the viewed items
            viewedItems.push(productId);
            setCookie("viewedItems", JSON.stringify(viewedItems), 7); // Save for 7 days
        }
    });

    function getCookie(name) {
        var cookieArr = document.cookie.split(";");
    
        for(var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");
            
            if(name == cookiePair[0].trim()) {
                return decodeURIComponent(cookiePair[1]);
            }
        }
        
        return null;
    }
});