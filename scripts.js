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
        var fid = $(this).data('fid');
        removeFromCart(fid)
    });

    $('.add-to-cart').click(function() {
        var fid = $(this).data('fid'); // Get food ID from the button
        addToCart(fid);  // Call function to add to cart
    });

    function removeFromCart(fid) { // Pass the food ID as an argument
        $.ajax({
            url: "cart.php",
            type: "POST", // Use POST for modifying data (like removing)
            data: { fid: fid, action: 'remove' },
            success: function(response) {
                if (response === 'success') {
                    // Successful removal, update the cart display
                    location.reload(); // Simplest approach, but you can improve this
    
                } else {
                    alert("Error removing item from cart: " + response);
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


