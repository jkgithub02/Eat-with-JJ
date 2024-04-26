// Checkout Functionality
// Get the button element
const saveButton = document.getElementById('save-order-details');

// Attach an event listener to the button, executing the 'saveOrderDetailsLocally' function when it's clicked.
saveButton.addEventListener('click', saveOrderDetailsLocally);

// **Saving Order Details (Locally)**
function saveOrderDetailsLocally() {
    // Create an object to hold the order information
    const orderData = {
    name: document.getElementById('name').value, //name 
    email: document.getElementById('email').value, //email
    phone: document.getElementById('phone').value, //phone
    address: document.getElementById('address').value //address
    };

    // Store in local storage
    localStorage.setItem('orderDetails', JSON.stringify(orderData)); 

    //sweet alert to show details saved
    Swal.fire({
        title: "Details have been successfully saved!",
        icon: "success"
    });
    
      // Locate the feedback element for displaying the save status
    const feedbackElement = document.getElementById('save-details-feedback');
    feedbackElement.textContent = "Details Saved!";
    feedbackElement.style.color = "green"; // Style the feedback text as green

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

//order button handler
const placeOrderButton = document.getElementById('place-order-button');
placeOrderButton.addEventListener('click', function() {
    saveOrderDetailsLocally();
    submitOrder();
});

//submit order function
function submitOrder() {
    const orderItems = cartItems.map(item => ({
        fid: item.fid, //food id
        foodname: item.foodname, //food name
        quantity: item.quantity, //quantity
        price: item.price //price
    }));

    //order details
    const orderData = {
        userId: userId, // userid
        name: document.getElementById('name').value, //name
        email: document.getElementById('email').value, //email
        phone: document.getElementById('phone').value, //phone
        address: document.getElementById('address').value, //address
        orderItems: orderItems, //get order items 
        date: calculateOrderDate(), //calculate date
        orderPlaced: true // Mark the order as placed
    };

    const paymentForm = document.getElementById('paymentForm'); 
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

    if (selectedPayment === "Credit/Debit Card") {
        //redirect to card payment page
        window.location.href = "card_payment.php";
    } else if (selectedPayment === "Cash") {
        //direct process order if cash is selected
        fetch('process_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            //clear the cart if order successful
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

// function to clear the cart
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


//function to calculate the order date and time
function calculateOrderDate() {
    const today = new Date(); //date
    const year = today.getFullYear(); //year
    const month = (today.getMonth() + 1).toString().padStart(2, '0'); //month
    const day = today.getDate().toString().padStart(2, '0'); //day

    const hours = today.getHours().toString().padStart(2, '0'); //hour
    const minutes = today.getMinutes().toString().padStart(2, '0'); //minute
    const seconds = today.getSeconds().toString().padStart(2, '0'); //second

    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`; //format date and time
    return formattedDateTime; //reutrn formatted time and date
}

