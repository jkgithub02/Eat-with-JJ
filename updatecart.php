<?php
session_start();

// Database connection (replace with your credentials)
include('connection.php');

if (isset($_POST['fid']) && isset($_POST['action'])) {
    $fid = intval($_POST['fid']);
    $action = $_POST['action'];

    // Handle actions:
    switch ($action) {
        case 'increase':
            if (isset($_SESSION['cart'])) {
                for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                    if ($_SESSION['cart'][$i]['fid'] == $fid) {
                        $_SESSION['cart'][$i]['quantity']++;
                        break;
                    }
                }
            }
            break;

        case 'decrease':
            if (isset($_SESSION['cart'])) {
                for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                    if ($_SESSION['cart'][$i]['fid'] == $fid) {
                        if ($_SESSION['cart'][$i]['quantity'] > 1) {
                            $_SESSION['cart'][$i]['quantity']--;
                        }
                        break; 
                    }
                }
            }
            break;

        case 'remove':
            if (isset($_SESSION['cart'])) {
                for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                    if ($_SESSION['cart'][$i]['fid'] == $fid) {
                        unset($_SESSION['cart'][$i]);
                        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
                        break; 
                    }
                }
            }
            break;

        default:
            echo "error: invalid action"; // Handle invalid action for security
            exit();
    }

    echo "success"; // Return a simple success response to the AJAX call
} else {
    echo "error: missing data";
}
?>