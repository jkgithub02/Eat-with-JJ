// Cart Functionality
// ensure document is ready first
$(document).ready(function() {
    //decrease cart item quantity click handler
    $('.decrease-quantity').click(function() {
        var fid = $(this).data('fid');
        decreaseQuantity(fid); 
    });

    //increase cart item quantity click handler
    $('.increase-quantity').click(function() {
        var fid = $(this).data('fid');
        increaseQuantity(fid); 
    });

    // remove item button click handler
    $('.remove-item').click(function() {
        var fid = $(this).data('fid');
        removeFromCart(fid)
    });

    //add to cart button click handler
    $('.add-to-cart').click(function() {
        if (!isLoggedIn()) { // login authentication
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'Please login or create an account to add items to your cart.',
                showCancelButton: true,
                confirmButtonText: 'Login', //login button 
                cancelButtonText: 'Continue Shopping' //continue browsing without login
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php'; // redirect to login page if login pressed
                }
            });
            return; // stop the button's default action
        }

        var fid = $(this).data('fid'); // Get food ID from the button
        var quantity = $(this).closest('.menu-item').find('.quantity').val(); //get quantity from field
        addToCart(fid,quantity);  // call function to add to cart
    });

    //decrease quantity function
    function decreaseQuantity(fid) {
        $.ajax({
            url: "cart.php", //webpage
            type: "POST", // Use POST for modifying data 
            data: { fid: fid, action: 'decrease' }, //decr4ease number
            success: function(response) {
                if (response === 'success') {
                    // update cart display
                    location.reload(); //reload webpage
                } else {
                    alert("Error decreasing quantity: " + response); //alert if failed to decrease
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }    

    //increase quantity function
    function increaseQuantity(fid) {
        $.ajax({
            url: "cart.php", //webpage
            type: "POST", // Use POST for modifying data 
            data: { fid: fid, action: 'increase' }, //increase number
            success: function(response) {
                if (response === 'success') {
                    // update cart display
                    location.reload(); // reload webpage
                } else {
                    alert("Error increasing quantity: " + response); //alert if failed to increase
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }    

    //remove item function
    function removeFromCart(fid) { // Pass the food ID as an argument
        $.ajax({
            url: "cart.php",
            type: "POST", // Use POST for modifying data 
            data: { fid: fid, action: 'remove' }, //remove item
            success: function(response) {
                if (response === 'success') {
                    // Successful removal, update the cart display
                    location.reload(); // reload webpage
    
                } else {
                    alert("Error removing item from cart: " + response); //alert if update failed
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + ", " + errorThrown);
            }
        });
    }

    //add to cart function
    function addToCart(fid, quantity) {
        $.ajax({
            url: "cart.php",
            type: "GET", // Use GET for addToCart operation
            data: { fid: fid, quantity: quantity, action: 'add' },
            success: function(response) {
                //sweet alert if item added to cart successfully
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

    //login authentication function
    function isLoggedIn() {
        let loggedIn = false;
        $.ajax({
            url: "check_login.php", 
            type: "GET", //Use GET to obtain 
            async: false, // Make the AJAX request synchronous
            success: function(response) {
                if (response === 'true') {
                    loggedIn = true;
                }
            }
        });
        //returns login status
        return loggedIn;
    }
});


