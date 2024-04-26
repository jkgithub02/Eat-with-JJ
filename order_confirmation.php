<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include ('connection.php');

// Start the session for cart and user data
session_start();

$user_id = $_SESSION['user_id'];  // retrieve session's user id

// Prepare the query to select from the 'order' table
$sql = "SELECT * FROM orders WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); //bind user id parameter
$stmt->execute();
$result = $stmt->get_result();

// Handle the orders
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderId = $row['oid'];
        $orderDate = $row['date'];
    }
} else {
    echo "No orders found for this user.";
}

// Retrieve user details 
$userDetails = json_encode($_SESSION['user_id']); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        const userDetails = <?php echo $userDetails; ?>;
    </script>
    <!-- javascript links  -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Eat with JJ - Order Confirmation</title>
    <!-- css  -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="pagewithbg">
    <?php include ('header.php'); ?>
    <!-- show successful order message  -->
    <div class="cartheading">
        <h1>Thank you for your order!</h1>
        <p>Your order has been placed and is being processed.</p>
        <!-- button to save receipt for order details  -->
        <button class="savepdf" id="savePDF123">Save Receipt as PDF</button>
    </div>


    <script>
        // return data as json 
        var userId = <?php echo json_encode($user_id); ?>;
    </script>
    <script src="receipt.js"></script>
</body>

</html>