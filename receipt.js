// saveReceiptasPDF

const receiptButton = document.getElementById('savePDF123');

// Add click event listener 
receiptButton.addEventListener('click', saveReceiptasPDF);

function saveReceiptasPDF() {

    let yPos = 20;

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Customize receipt content. Replace with your actual order data
    doc.setFontSize(16); // Slightly larger for heading
    doc.text('Order Receipt - Eat with JJ', 20, 10); 
    doc.line(20, 15, 180, 15); // Visual divider
    // Add User Details (modify if your storage differs)
    const orderDetails = localStorage.getItem('orderDetails');
    if (orderDetails) {
        const user = JSON.parse(orderDetails);

        doc.setFontSize(12);
        yPos += 5;
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

    yPos += 20;

    // Order Table
    doc.setFontSize(12);
    doc.text('Item', 20, yPos);
    doc.text('Qty', 100, yPos);
    doc.text('Price (RM)', 140, yPos);
    doc.text('Subtotal (RM)', 180, yPos);
    yPos += 10; // Move down for items
    
    // Fetch and Process Orders
    fetchOrderDetails(userId) // Assuming 'user' is the way you identify the user
        .then(orderItems => {
            let total = 0;
            let subtotal = 0;

            yPos += 10;

            orderItems.forEach(item => {
                subtotal = item.quantity * item.price;
                doc.text(`${item.foodname}`, 20, yPos);
                doc.text(`${item.quantity}`, 100, yPos);
                doc.text(`${item.price}`, 140, yPos);
                doc.text(`${subtotal.toFixed(2)}`, 180, yPos);

                total += subtotal;
                yPos += 10;
                

                yPos += 2; // Add space before next order
            });

            yPos += 40;
            doc.text('Total:', 140, yPos);
            doc.text(`RM${total.toFixed(2)}`, 180, yPos);

            // Company Info (bottom)
            yPos += 20; 
            doc.setFontSize(10); // Smaller for company info
            // Add logo if available
            doc.text('Eat with JJ', 20, yPos);

            doc.save('order-receipt.pdf');
            Swal.fire({
                title: "Receipt has been successfully generated.",
                // text: "That thing is still around?",
                icon: "success"
            });
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