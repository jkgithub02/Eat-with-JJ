<?php
// Enable error reporting for development
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include('connection.php');

// Start the session for cart management 
session_start();

// Initialize the cart if it's empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handling 'Add to Cart' action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['fid'])) {
    $fid = intval($_GET['fid']); // Sanitize input

    // Fetch food details
    $sql = "SELECT * FROM food WHERE fid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if item is already in cart
        $itemFound = false;
        for ($i = 0; $i < count($_SESSION['cart']); $i++) {
            if ($_SESSION['cart'][$i]['fid'] == $fid) {
                $_SESSION['cart'][$i]['quantity']++;
                $itemFound = true;
                break; 
            }
        }

        // If not already existing, add it as a new item
        if (!$itemFound) {
            $_SESSION['cart'][] = array(
                'fid' => $row['fid'],
                'foodname' => $row['foodname'],
                'price' => $row['price'],
                'quantity' => 1 
            );
        }

        // Redirect to cart to show updated state
        header("Location: cart.php"); 
        exit; 

    } else {
        // Handle the case where the food item is not found
        echo "Invalid food item"; 
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'remove' && isset($_POST['fid'])) {
    $fid = intval($_POST['fid']); 

    // Find the item in the cart
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['fid'] == $fid) {
            unset($_SESSION['cart'][$i]); // Remove the item
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
            echo "success"; // Return success response
            exit;
        }
    }

    echo "error: item not found"; // Handle the case where the item is not found
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
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <?php include('header.php'); ?>
    <main>
        <h1>Your Shopping Cart</h1>

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
                        <th>Actions</th>
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
                        <td>
                            <button class="update-quantity">-</button>
                            <button class="remove-item" data-fid="<?= $item['fid'] ?>">Remove</button>
                        </td>
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

            <a href="checkout.php" class="button">Proceed to Checkout</a> 
        <?php } ?>           

    </main>
    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>
    <script src="scripts.js"></script>
</body>
</html>
