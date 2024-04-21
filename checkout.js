// Checkout Functionality
// Get the button element
const saveButton = document.getElementById('save-order-details');

// Add click event listener 
saveButton.addEventListener('click', saveOrderDetailsLocally);

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

    Swal.fire({
        title: "Details have been successfully saved!",
        // text: "That thing is still around?",
        icon: "success"
    });
    
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
      document.getElementById('phone').value = orderData.phone;
      document.getElementById('address').value = orderData.address;
    }
}  




const placeOrderButton = document.getElementById('place-order-button');
placeOrderButton.addEventListener('click', function() {
    saveOrderDetailsLocally();
    submitOrder();
});


function submitOrder() {
    const orderItems = cartItems.map(item => ({
        fid: item.fid,
        foodname: item.foodname,
        quantity: item.quantity,
        price: item.price
    }));

    const orderData = {
        userId: userId, // Assuming `userId` variable exists
        name: document.getElementById('name').value, 
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        orderItems: orderItems,
        date: calculateOrderDate(),
        orderPlaced: true // Mark the order as placed
    };

    const paymentForm = document.getElementById('paymentForm');
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

    if (selectedPayment === "Credit/Debit Card") {
        // paymentForm.action = "card_payment.php"; 
        window.location.href = "card_payment.php";
    } else if (selectedPayment === "Cash") {
        fetch('process_order.php', { // Send to your PHP order processing script
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearCart();
                window.location.href = "order_confirmation.php";
            } else {
                alert("Error placing order: " + data.message); 
            }
        })
        .catch(error => console.error('Error submitting order:', error));
    }


}

// Helper to clear the cart
function clearCart() {
    cartItems = []; // Reset the cartItems array
    localStorage.removeItem('cartItems');
    // Update your cart display to reflect an empty cart
    fetch('clear_cart_session.php') // Replace with the correct URL for your server-side logic
       .then(response => {
          // Handle the response from the server (if needed)
       })
       .catch(error => {
           console.error('Error clearing session:', error);
       });
}

function calculateOrderDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0'); 
    const day = today.getDate().toString().padStart(2, '0');

    const hours = today.getHours().toString().padStart(2, '0');
    const minutes = today.getMinutes().toString().padStart(2, '0');
    const seconds = today.getSeconds().toString().padStart(2, '0');

    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`; 
    return formattedDateTime;
}

