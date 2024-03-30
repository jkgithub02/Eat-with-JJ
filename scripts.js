$(document).ready(function() {
    // Update quantity button click handler
    $('.update-quantity').click(function() {
        var row = $(this).closest('tr');
        var fid = row.find('td:first-child').text(); 
        var action = $(this).text() === '-' ? 'decrease' : 'increase'; 
        updateCartItem(fid, action);
    });

    // Remove item button click handler
    $('.remove-item').click(function() {
        var row = $(this).closest('tr');
        var fid = row.find('td:first-child').text(); 
        updateCartItem(fid, 'remove');
    });

    $('.add-to-cart').click(function() {
        var fid = $(this).data('fid'); // Get food ID from the button
        addToCart(fid);  // Call function to add to cart
    });

    // updateCartItem function (same as before)
    function updateCartItem(fid, action) {
        $.ajax({
            url: "updatecart.php", // Send request to a separate script for cart updates
            type: "POST",
            data: { fid: fid, action: action },
            success: function(response) {
                if (response === 'success') {
                    // Update cart display on success
                    location.reload(); // Simplest approach, reloads the entire page
                } else {
                    alert("Error updating cart: " + response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }

    function addToCart(fid) {
        $.ajax({
            url: "cart.php",
            type: "GET", // Use GET for addToCart operation
            data: { fid: fid, action: 'add' },
            success: function(response) {
                // Optionally, provide feedback for successful addition
                alert("Item added to cart!");

                // ... (Any further actions you want to execute)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }

});


