<?php
    session_start();
    include ('connection.php');

    $foodId = $_POST['fid'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    if ($foodId) {
        // Sanitize input (you should do more thorough validation)
        $foodId = (int) $foodId;
        $quantity = (int) $quantity;

        // Update the cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][$foodId] = $quantity;

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid food ID']);
    }
?>