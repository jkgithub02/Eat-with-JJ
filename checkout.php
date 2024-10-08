<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include ('connection.php');

// Start the session for cart and user data
session_start();

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect to cart if empty
    exit;
}

// retrieve user id from session
$user_id = $_SESSION['user_id'];  

// Prepare the query
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); //bind user id parameter
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
    <!-- javascript links  -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Eat with JJ - Checkout</title>
    <!-- css  -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="pagewithbg">
    <!-- header  -->
    <?php include ('header.php'); ?>
    <main>
        <!-- div for title  -->
        <div class="cartheading">
            <h1>Checkout</h1>
        </div>

        <?php
        // empty cart 
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is empty.</p>";
        } else {
            ?>
            <!-- cart table  -->
            <div class="carttable">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // calculate total 
                        $total = 0;
                        //use a loop to retrieve item details
                        foreach ($_SESSION['cart'] as $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= $item['foodname'] ?></td>
                                <td>RM<?= $item['price'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>RM<?= $subtotal ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <!-- column span for neater table view  -->
                            <td colspan="3" class="text-right">Total:</td>
                            <td>RM<?= $total ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php } ?>
    </main>
    <!-- billing form details  -->
    <div class="cartheading">
        <h1>Billing Details</h1>
        <section class="form-wrapper">
            <section class="form-container">
                <!-- form for billing details  -->
                <form method="POST" action="checkout.php">
                    <label for="name">Full Name:</label>
                    <!-- autofill original details from userid details for name email phone and address -->
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                        required><br><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                        required><br><br>

                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone"
                        value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>

                    <label for="address">Address:</label>
                    <input type="address" id="address" name="address"
                        value="<?php echo htmlspecialchars($user['address']); ?>"><br><br>

                    <button type="button" class="save-order" id="save-order-details">Save Details for This
                        Order</button>
                    <p id="save-details-feedback"></p>
                    <p>These details will only be saved for this order.</p>
                    <p>If you would like to change your permanently change your details, please change them on the
                        profile page.</p>
                </form>
            </section>
        </section>
    </div>
    <div class="cartheading">
        <h1>Payment Options</h1>
        <!-- selection for payment method  (cash, credit/debit) -->
        <form id="paymentForm">
            <input type="radio" id="card" name="payment_method" value="Credit/Debit Card">
            <label for="html">Credit/Debit Card</label><br>
            <input type="radio" id="cash" name="payment_method" value="Cash"> 
            <label for="cash">Cash</label>
        </form>
        <button class="place-order" id="place-order-button">Place Order</button>
    </div>
    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>
    <script>
        //convert values to json string format
        var userId = <?php echo json_encode($user_id); ?>;
        var cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
    </script>
    <script src="checkout.js"></script>
</body>

</html>