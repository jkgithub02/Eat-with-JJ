<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include ('connection.php');

// Start the session for cart and user data
session_start();

$user_id = $_SESSION['user_id'];  // (Or however you retrieve the user ID)

// Prepare the query to select from the 'order' table
$sql = "SELECT * FROM `orders` WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle the orders (you may want to loop through them)
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderId = $row['oid'];
        $orderDate = $row['date'];
    }
} else {
    echo "No orders found for this user.";
}

// Retrieve user details (you might need to adjust this)
$userDetails = json_encode($_SESSION['user_id']); // Assuming user data is in session 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        const userDetails = <?php echo $userDetails; ?>;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Eat with JJ - Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <div class="cartheading">
        <h1>Thank you for your order!</h1>
        <p>Your order has been placed and is being processed.</p>
        <button class="savepdf" id="savePDF123">Save Receipt as PDF</button>
    </div>


    <script>
        var userId = <?php echo json_encode($user_id); ?>;
    </script>
    <script src="receipt.js"></script>
</body>

</html>