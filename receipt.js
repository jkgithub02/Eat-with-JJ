// saveReceiptasPDF

const receiptButton = document.getElementById('savePDF123');

// Add click event listener 
receiptButton.addEventListener('click', saveReceiptasPDF);

function saveReceiptasPDF() {

    let yPos = 20;

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Customize receipt content. Replace with your actual order data
    doc.text('Eat with JJ - Order Receipt', 20, 10);
    // Add User Details (modify if your storage differs)
    const orderDetails = localStorage.getItem('orderDetails'); 
    if (orderDetails) {
        const user = JSON.parse(orderDetails); 

    doc.text(`Name: ${user.name}`, 20, yPos);
    yPos += 10;
    doc.text(`Email: ${user.email}`, 20, yPos);
    yPos += 10;
    doc.text(`Phone: ${user.phone}`, 20, yPos);
    yPos += 10;
    doc.text(`Address: ${user.address}`, 20, yPos);
    yPos += 10;
    } else {
        // Handle the case where user details are not found
        console.error("User details not found in local storage");
    }

    // Fetch and Process Orders
    fetchOrderDetails(userId) // Assuming 'user' is the way you identify the user
        .then(orderItems => {
            let total = 0;

            doc.text('Items:', 20, yPos);  
            yPos += 10;

            orderItems.forEach(item => {
                doc.text(`${item.foodname} (x${item.quantity}) - $${item.price}`, 20, yPos);
                yPos += 10; 
                total += item.price * item.quantity;

                yPos += 20; // Add space before next order
            });

            doc.text(`Total: $${total.toFixed(2)}`, 20, yPos);
            doc.save('order-receipt.pdf');
        })
        .catch(error => {
            console.error("Error fetching order details:", error);
            console.error("Error Message:", error.message); // Access Error Message  
            console.error("Error Status:", error.status);    // Access Error Status
        });

}


function fetchOrderDetails(user) { 
    return $.ajax({
        url: 'get_order_details.php', 
        type: 'GET',
        dataType: 'json',
        data: { user: user } 
    }).then(data => {
        // Ensure data is an array of orders
        if (!Array.isArray(data)) {
            throw new Error("Unexpected data format from server. Expected an array of orders.");
        }
        return data; // Return the array of orders
    }); 
}