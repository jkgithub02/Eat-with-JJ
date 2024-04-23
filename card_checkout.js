const placeOrderButton = document.getElementById('place-order-button');
const cardPaymentForm = document.getElementById('cardPaymentForm');
placeOrderButton.addEventListener('click', function () {
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

function validatePaymentDetails() {
    const cardNumber = document.getElementById("cardNumber").value;
    const expiryDate = document.getElementById("expiryDate").value;
    const cvv = document.getElementById("cvv").value;

    // 1. Card Number Validation
    const cardNumberRegex = /^[0-9\s]{13,19}$/;
    if (!cardNumberRegex.test(cardNumber)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Card Number',
            text: 'Please enter a valid card number (digits and spaces only).'
        });
        return false;
    }

    // 2. Expiry Date Validation
    if (!expiryDate) {
        Swal.fire({
            icon: 'error',
            title: 'Empty Date',
            text: 'Please enter the expiry date.'
        });
        return false;
    } else {
        // Basic check (you can enhance this for more robust expiry validation)
        const expiryDate = document.getElementById("expiryDate").value;
        const [year, month] = expiryDate.split('-');
        const today = new Date();
        const currentMonth = today.getMonth() + 1; // Months are 0-indexed
        const currentYear = today.getFullYear();
        if (Number(year) < currentYear || (Number(year) === currentYear && Number(month) < currentMonth)) {
            Swal.fire({
                icon: 'error',
                title: 'Wrong Date',
                text: 'Please enter a valid expiry date that is not in the past.'
            });
            return false;
        }
    }

    // 3. CVV Validation
    if (isNaN(cvv) || !cvv.match(/^\d{3}$/)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid CVV',
            text: 'Please enter a valid CVV (digits only).'
        });
        return false;
    }

    return true;
}

