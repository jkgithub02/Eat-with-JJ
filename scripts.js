////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
            alert("Please log in to add items to your cart.");
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
                // Optionally, provide feedback for successful addition
                alert("Item added to cart!");

                // ... (Any further actions you want to execute)
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Checkout Functionality

// Inside your existing 'save-order-details' button click handler
function saveOrderDetailsLocally() {
    const orderData = {
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      phone: document.getElementById('phone').value,
      address: document.getElementById('address').value 
    };
  
    // Store in local storage
    localStorage.setItem('orderDetails', JSON.stringify(orderData)); 

    // Show feedback message
    const feedbackElement = document.getElementById('save-details-feedback');
    feedbackElement.textContent = "Details Saved!";
    feedbackElement.style.color = "green"; // Optional: style for success

    // Clear feedback after a few seconds
    setTimeout(function() {
        feedbackElement.textContent = "";
    }, 3000); // 3 seconds delay
}

// Function to load from local storage (call on page load or when needed)
function loadOrderDetails() {
    const storedData = localStorage.getItem('orderDetails');
    if (storedData) {
      const orderData = JSON.parse(storedData);
      document.getElementById('name').value = orderData.name;
      document.getElementById('email').value = orderData.email;
      ocument.getElementById('phone').value = orderData.phone;
      ocument.getElementById('address').value = orderData.address;
    }
}  

// Get the button element
const saveButton = document.getElementById('save-order-details');

// Add click event listener 
saveButton.addEventListener('click', saveOrderDetailsLocally);

// Load details on page load (optional)
window.addEventListener('load', loadOrderDetails); 


const placeOrderButton = document.getElementById('place-order-button');
placeOrderButton.addEventListener('click', submitOrder);

function submitOrder() {
    // Cart items - simplified structure
    const cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
    const orderItems = cartItems.map(item => ({
        fid: item.id, // Assuming you have an 'id' property for food items
        foodname: item.foodname,
        quantity: item.quantity,
        price: item.price
    }));

    // Order data with simplified structure
    const orderData = {
        userId: <?php echo $user_id; ?>,
        name: document.getElementById('name').value, 
        // ... (other fields) 
        orderItems: orderItems,
        date: calculateOrderDate() // You'll need a function for this
    };

    // 2. Send data to the backend (checkout.php) using AJAX
    fetch('checkout.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json' 
      },
      body: JSON.stringify(orderData)
    })
    .then(response => response.json()) // Expect JSON response from checkout.php
    .then(data => {
      // Handle success or error messages from checkout.php
      if (data.success) {
        alert("Order placed successfully!");
        // Potentially clear cart and local storage
      } else {
        alert("Error placing order: " + data.message); 
      }
    })
    .catch(error => console.error('Error submitting order:', error));
}

function calculateOrderDate() {
    // Implement logic to get the current date in the correct format (e.g., YYYY-MM-DD)
    const today = new Date();
    // ... format the date ... 
    return formattedDate; 
}