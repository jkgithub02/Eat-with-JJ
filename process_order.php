<?php 
// Enable error reporting for development
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection
include('connection.php');

// Start the session for cart and user data
session_start();

$orderData = json_decode(file_get_contents('php://input'), true);

// Retrieve user data
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $orderData['userId']); //get uid in orderdata
$stmt->execute();
$result = $stmt->get_result();

//if failed to find user
if ($result->num_rows !== 1) {
    echo json_encode(['success' => false, 'error' => 'User data not found']);
    exit();
}
$user = $result->fetch_assoc(); 

if ($orderData['orderPlaced']) { 
    // Prepare the main orders table insertion
    $sql = "INSERT INTO orders (uid, fid, quantity, sid, date) VALUES (?, ?, ?, 0, ?)";
    $stmt = $conn->prepare($sql);

    // if sql statement cannot be executed
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Database preparation failed']);
        exit();
    }

    //use loop to insert order items into the database
    foreach ($orderData['orderItems'] as $item) {
        $stmt->bind_param('iiis', $orderData['userId'], $item['fid'], $item['quantity'], $orderData['date']);

        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Database insertion failed']);
            exit();
        }
    }

    // Everything succeeded
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Order not yet placed']);
}
?>