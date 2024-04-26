const placeOrderButton = document.getElementById('place-order-button'); //gets the place order button
const cardPaymentForm = document.getElementById('cardPaymentForm'); //gets the cardpayment form
//click listenter for order button
placeOrderButton.addEventListener('click', function () {
    //only submit order if payment details are validated
    if (validatePaymentDetails()) {
        submitOrder();
    }
});

//submit order function
function submitOrder() {
    //get ordered items from cart
    const orderItems = cartItems.map(item => ({
        fid: item.fid, //food id
        foodname: item.foodname, //food name
        quantity: item.quantity, //quantity
        price: item.price //price
    }));

    const orderData = {
        userId: userId, // get user id
        orderItems: orderItems, //get order items as shown above
        date: calculateOrderDate(), //get order time
        orderPlaced: true // mark the order as placed
    };

    fetch('process_order.php', { // send to PHP order processing script
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData) //converts order data into strings
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearCart(); //clears cart if order successful
                window.location.href = "order_confirmation.php"; //redirection to order confirmation page
            } else {
                alert("Error placing order: " + data.message); //failed to order
            }
        })
        .catch(error => console.error('Error submitting order:', error)); //catching the error when ordering

}

// clears the cart
function clearCart() {
    cartItems = []; // reset the cartItems array
    localStorage.removeItem('cartItems'); //remove cart items
    // update cart display to reflect an empty cart
    fetch('clear_cart_session.php') // replace with the correct URL for your server-side logic
        .then(response => {
            // Handle the response from the server (if needed)
        })
        .catch(error => {
            console.error('Error clearing session:', error);
        });
}

//get order time and date
function calculateOrderDate() {
    const today = new Date(); //date
    const year = today.getFullYear(); //year
    const month = (today.getMonth() + 1).toString().padStart(2, '0'); //month
    const day = today.getDate().toString().padStart(2, '0'); //day 

    const hours = today.getHours().toString().padStart(2, '0'); //hour
    const minutes = today.getMinutes().toString().padStart(2, '0'); //minute
    const seconds = today.getSeconds().toString().padStart(2, '0'); //second

    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`; //format the date 
    return formattedDateTime; //returns formatted date with time
}

//validate payment details
function validatePaymentDetails() {
    const cardNumber = document.getElementById("cardNumber").value; //gets card number
    const expiryDate = document.getElementById("expiryDate").value; //gets expiry date
    const cvv = document.getElementById("cvv").value; //gets cvv 

    // 1. Card Number Validation
    const cardNumberRegex = /^[0-9\s]{13,19}$/;
    if (!cardNumberRegex.test(cardNumber)) {
        //sweet alert to handle invalid card number input
        Swal.fire({
            icon: 'error',
            title: 'Invalid Card Number',
            text: 'Please enter a valid card number (digits and spaces only).'
        });
        //returns false to indicate error in validation
        return false;
    }

    // 2. Expiry Date Validation
    //expiry date not filled in
    if (!expiryDate) {
        //sweet alert for empty date
        Swal.fire({
            icon: 'error',
            title: 'Empty Date',
            text: 'Please enter the expiry date.'
        });
        //returns false to indicate error in validation
        return false;
    } else {
        const expiryDate = document.getElementById("expiryDate").value; //get expiry date
        const [year, month] = expiryDate.split('-'); //process the expiry date for comparison
        const today = new Date(); //gets today date
        const currentMonth = today.getMonth() + 1; // months are 0-indexed, so add 1
        const currentYear = today.getFullYear(); //get year
        //validates if entered expiry date is valid and not in the past
        if (Number(year) < currentYear || (Number(year) === currentYear && Number(month) < currentMonth)) {
            //sweet alert for past dates
            Swal.fire({
                icon: 'error',
                title: 'Wrong Date',
                text: 'Please enter a valid expiry date that is not in the past.'
            });
            //returns false to indicate error in validation
            return false;
        }
    }

    // 3. CVV Validation
    //cvv must be 3 digits
    if (isNaN(cvv) || !cvv.match(/^\d{3}$/)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid CVV',
            text: 'Please enter a valid CVV (digits only).'
        });
        //returns false to indicate error in validation
        return false;
    }

    //return true only if all validations passed
    return true;
}

