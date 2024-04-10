<?php
// Enable error reporting for development
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include('connection.php');

// Start the session for cart and user data
session_start();

// Check if the cart is empty
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
    <?php include('header.php'); ?>
    <main>
        <h1>Checkout</h1>

        <?php
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is empty.</p>";
        } else {
            ?>
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
                    $total = 0;
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
                        <td colspan="3" class="text-right">Total:</td>
                        <td>RM<?= $total ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </main>
    <div class="checkout-form">
        <h2>Billing Details</h2>
        <section class="form-wrapper">
        <section class="form-container">
            <form method="POST" action="checkout.php">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>

                <label for="address">Address:</label>
                <input type="address" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" ><br><br>

                <button type="button" id="save-order-details">Save Details for This Order</button>
                <p id="save-details-feedback"></p>
                <p>These details will only be saved for this order.</p>
                <p>If you would like to change your permanently change your details, please change them on the profile page.</p>
            </form>
        </section>
        </section>
    </div>
    <div class="payment-section">
        <h2>Payment Options</h2>
            </div>
    
    <button class="place-order" id="place-order-button">Place Order</button> 
    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>
    <script>
        var userId = <?php echo json_encode($user_id); ?>;
        var cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
    </script>
    <script src="checkout.js"></script> 
</body>
</html>

