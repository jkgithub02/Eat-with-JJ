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
?>

<!DOCTYPE html>
<html lang="en">
<script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts.js"></script>
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
    </div>
    <div class="payment-section">
        <h2>Payment Options</h2>
            </div>
    <button class="place-order">Place Order</button> 
    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>
    <script src="scripts.js"></script> 
</body>
</html>