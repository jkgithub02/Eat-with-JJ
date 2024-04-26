<?php
// enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// database connection 
include ('connection.php');

// dtart the session for cart and user data
session_start();

if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // redirect to cart if empty
    exit;
}

// retrieve user id from session
$user_id = $_SESSION['user_id'];  

// prepare the query to get user details from database
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); //bind uid parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // handle the case where the user is not found ( but unlikely if the session is valid)
    echo "User data not found.";
    exit();
}

// get the result
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- import scripts  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Eat with JJ - Checkout</title>
    <!-- link with css file -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="pagewithbg">
    <!-- header -->
    <?php include ('header.php'); ?>
    <main>
        <!-- heading -->
        <div class="cartheading">
            <h1>Card Payment</h1>
        </div>

        <!-- div for card payment form -->
        <div class="form-wrap">
            <!-- card payment form -->
            <form method="POST" action="order_confirmation.php" id="cardPaymentForm" onsubmit="return validatePaymentDetails()">
            <!-- limit input length to 16 and only numnbers for card number -->
                <label for="cardNumber">Card Number:</label>
                <input type="tel" id="cardNumber" name="cardNumber" inputmode="numeric" pattern="[0-9\s]{13,19}"
                    autocomplete="cc-number" maxlength="16" placeholder="xxxx xxxx xxxx xxxx" required>

                <!-- month and year input only for expiry date -->
                <label for="expiryDate">Expiry Date (MM/YY):</label>
                <input type="month" id="expiryDate" name="expiryDate" required>

                <!-- set cvv as password for security and only allow max length of 3 -->
                <label for="cvv">CVV:</label>
                <input type="password" id="cvv" name="cvv" inputmode="numeric" maxlength="3" required>

                <!-- place order button -->
                <button type="submit" class="place-order" id="place-order-button">Place Order</button>
            </form>
        </div>
    </main>

    <!-- sweet alert and javascript file src  -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="card_checkout.js"></script>
</body>

<!-- js script to encode userid and cart  -->
<script>
    var userId = <?php echo json_encode($user_id); ?>;
    var cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
</script>


</html>