<?php
include('header.php');   
session_start();
  include ('connection.php');
 ?>

// Login Protection
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

// Database Connection (replace with your credentials)
include ('connection.php'); 

// Retrieve Cart from Session
$cart = $_SESSION['cart'] ?? [];

// Fetch Real Food Data from Database
if (!empty($cart)) {
    $foodIds = array_keys($cart); 
    $placeholders = str_repeat('?,', count($foodIds) - 1) . '?';
    $sql = "SELECT fid, foodname, price FROM food WHERE fid IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($foodIds)), ...$foodIds); 
    $stmt->execute();
    $result = $stmt->get_result();

    $foodDetails = [];
    while ($row = $result->fetch_assoc()) {
        $foodDetails[$row['fid']] = $row; 
    }
} 

// Calculate Total
$total = 0;
foreach ($cart as $itemId => $quantity) {
    if (isset($foodDetails[$itemId])) {
        $subtotal = $foodDetails[$itemId]['price'] * $quantity;
        $total += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if (empty($cart)) : ?>
        <p>Your cart is empty.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $itemId => $quantity) : ?>
                    <?php if (isset($foodDetails[$itemId])) : ?>
                        <tr>
                            <td><?php echo $foodDetails[$itemId]['foodname']; ?></td>
                            <td><?php echo $quantity; ?></td> 
                            <td>RM<?php echo number_format($foodDetails[$itemId]['price'], 2); ?></td>
                            <td>RM<?php echo number_format($foodDetails[$itemId]['price'] * $quantity, 2); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total:</td>
                    <td>RM<?php echo number_format($total, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <form action="checkout.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">  
            <button type="submit">Checkout</button>
        </form>
    <?php endif; ?>
</body>
</html>
