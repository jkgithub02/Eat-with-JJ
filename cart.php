<?php
// enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// database connection 
include ('connection.php');

// start the session for cart management 
session_start();

// initialize the cart if it's empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// handling 'Add to Cart' action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['fid'])) {
    // Sanitize input
    $fid = intval($_GET['fid']); 
    $quantity = intval($_GET['quantity']); 

    // fetch food details from food table
    $sql = "SELECT * FROM food WHERE fid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fid); //bind food id parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // check if item is already in cart
        $itemFound = false;
        // use for loop to check for existing items
        for ($i = 0; $i < count($_SESSION['cart']); $i++) {
            if ($_SESSION['cart'][$i]['fid'] == $fid) {
                $_SESSION['cart'][$i]['quantity'] += $quantity;
                $itemFound = true;
                break;
            }
        }

        // if not already existing, add it as a new item
        if (!$itemFound) {
            $_SESSION['cart'][] = array(
                'fid' => $row['fid'],
                'foodname' => $row['foodname'],
                'price' => $row['price'],
                'quantity' => $quantity
            );
        }

        // redirect to cart webpage to show updated state
        header("Location: cart.php");
        exit;

    } else {
        // handle the case where the food item is not found
        echo "Invalid food item";
    }
}

//handling remove function
if (isset($_POST['action']) && $_POST['action'] == 'remove' && isset($_POST['fid'])) {
    //sanitize input
    $fid = intval($_POST['fid']);

    // find the item in the cart using for loop
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['fid'] == $fid) {
            unset($_SESSION['cart'][$i]); // remove the item
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex the array
            echo "success"; // return success response
            exit;
        }
    }

    echo "error: item not found"; // Handle the case where the item is not found
}


//handle the decrease function
if (isset($_POST['action']) && $_POST['action'] == 'decrease' && isset($_POST['fid'])) {
    // Sanitize input
    $fid = intval($_POST['fid']); 

    // Find the item in the cart using for loop
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['fid'] == $fid) {
            // Decrease quantity if it's greater than 1
            if ($_SESSION['cart'][$i]['quantity'] > 1) {
                $_SESSION['cart'][$i]['quantity']--;
                echo "success"; // Return success response
            } else {
                echo "error: minimum quantity"; // Example error response
            }
            exit; // Stop further processing
        }
    }

    // Handle the case where the item is not found
    echo "error: item not found";
}


//handle the increase function
if (isset($_POST['action']) && $_POST['action'] == 'increase' && isset($_POST['fid'])) {
    //sanitize input
    $fid = intval($_POST['fid']);

    // Find the item in the cart using for loop
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['fid'] == $fid) {
            // Increase quantity 
            $_SESSION['cart'][$i]['quantity']++;
            echo "success";
            exit;
        }
    }

    // Handle the case where the item is not found
    echo "error: item not found";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- javascripts and links for sweetalert and ajax  -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Your Cart</title>
    <!-- css  -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="pagewithbg">
    <?php include ('header.php'); ?>
    <main>
        <!-- cart title  -->
        <div class="cartheading">
            <h1>Your Shopping Cart</h1>
        </div>

        <?php
        // checks if cart is empty 
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is empty.</p>";
        } else {
            ?>
            <!-- generate cart table  -->
            <div class="carttable">
                <table>
                    <thead>
                        <tr>
                            <!-- cart table columns  -->
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
                        //calculate total for all items in the cart
                        foreach ($_SESSION['cart'] as $item) {
                            $subtotal = $item['price'] * $item['quantity']; //quantity * price
                            $total += $subtotal; //gets total price
                            ?>
                            <tr>
                                <!-- show table data  -->
                                <td><?= $item['foodname'] ?></td>
                                <td>RM<?= $item['price'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>RM<?= $subtotal ?></td>
                                <td>
                                    <!-- action buttons here  -->
                                    <button class="increase-quantity" data-fid="<?= $item['fid'] ?>">+</button>
                                    <button class="decrease-quantity" data-fid="<?= $item['fid'] ?>">-</button>
                                    <button class="remove-item" data-fid="<?= $item['fid'] ?>">Remove</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <!-- span the columns for neater table view  -->
                            <td colspan="4" class="text-right">Total:</td>
                            <td>RM<?= $total ?></td>
                        </tr>
                    </tfoot>
                </table>
                <!-- checkout button  -->
                <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
            </div>
        <?php } ?>
    </main>
    <!-- footer  -->
    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>
    <!-- javascript file for cart  -->
    <script src="cart.js"></script>
</body>

</html>