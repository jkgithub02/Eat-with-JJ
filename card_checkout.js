const placeOrderButton = document.getElementById('place-order-button');
placeOrderButton.addEventListener('click', function() {
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
        orderItems: orderItems,
        date: calculateOrderDate(),
        orderPlaced: true // Mark the order as placed
    };

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

