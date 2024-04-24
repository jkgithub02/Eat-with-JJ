// Cart Functionality

$(document).ready(function() {
    $('.decrease-quantity').click(function() {
        var fid = $(this).data('fid');
        decreaseQuantity(fid); 
    });

    $('.increase-quantity').click(function() {
        var fid = $(this).data('fid');
        increaseQuantity(fid); 
    });

    // Remove item button click handler
    $('.remove-item').click(function() {
        var fid = $(this).data('fid');
        removeFromCart(fid)
    });

    $('.add-to-cart').click(function() {
        if (!isLoggedIn()) { // Assuming you have an 'isLoggedIn' function
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'Please login or create an account to add items to your cart.',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Continue Shopping'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php'; // Redirect to login page
                }
            });
            return; // Stop the button's default action
        }

        var fid = $(this).data('fid'); // Get food ID from the button
        var quantity = $(this).closest('.menu-item').find('.quantity').val();
        addToCart(fid,quantity);  // Call function to add to cart
    });

    function decreaseQuantity(fid) {
        $.ajax({
            url: "cart.php",
            type: "POST", 
            data: { fid: fid, action: 'decrease' },
            success: function(response) {
                if (response === 'success') {
                    // Update cart display
                    location.reload(); // Simplistic, you may want more dynamic updates
                } else {
                    alert("Error decreasing quantity: " + response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }    

    function increaseQuantity(fid) {
        $.ajax({
            url: "cart.php",
            type: "POST", 
            data: { fid: fid, action: 'increase' },
            success: function(response) {
                if (response === 'success') {
                    // Update cart display
                    location.reload(); // Simplistic, you may want more dynamic updates
                } else {
                    alert("Error increasing quantity: " + response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }    

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

    function addToCart(fid, quantity) {
        $.ajax({
            url: "cart.php",
            type: "GET", // Use GET for addToCart operation
            data: { fid: fid, quantity: quantity, action: 'add' },
            success: function(response) {
                Swal.fire({
                    title: "Item has been successfully added to cart!",
                    icon: "success"
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }

    function isLoggedIn() {
        let loggedIn = false;
        $.ajax({
            url: "check_login.php", 
            type: "GET",
            async: false, // Make the AJAX request synchronous
            success: function(response) {
                if (response === 'true') {
                    loggedIn = true;
                }
            }
        });
        return loggedIn;
    }
});


