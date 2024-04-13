<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include ('connection.php');

// Start the session for cart and user data
session_start();

if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect to cart if empty
    exit;
}

// Assuming you know the user's ID
$user_id = $_SESSION['user_id'];  // (Or however you retrieve the user ID)

// Prepare the query
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Handle the case where the user is not found (unlikely if the session is valid)
    echo "User data not found.";
    exit();
}

// Get the result
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Eat with JJ - Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <main>
        <div class="cartheading">
            <h1>Card Payment</h1>
        </div>
        <div class="form-wrap">
                <form method="POST" id="cardPaymentForm">

                    <label for="cardNumber">Card Number:</label>
                    <input type="text" id="cardNumber" name="cardNumber" required>

                    <label for="expiryDate">Expiry Date (MM/YY):</label>
                    <input type="text" id="expiryDate" name="expiryDate" required>

                    <label for="cvv">CVV:</label>
                    <input type="password" id="cvv" name="cvv" required>
                    
                    <button type="submit" class="place-order" id="place-order-button" >Place Order</button>

                </form>
        </div>
    </main>
</body>
<script>
    var userId = <?php echo json_encode($user_id); ?>;
    var cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
</script>
<script src="card_checkout.js"></script>

</html>