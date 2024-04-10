<?php
// Enable error reporting for development
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Database connection (replace with your credentials)
include('connection.php');

// Start the session for cart and user data
session_start();

$user_id = $_SESSION['user_id'];

// Prepare and execute the query 
$sql = "SELECT foodname, quantity, price FROM orders WHERE uid = ? AND `date` = (SELECT MAX(`date`) FROM orders WHERE uid = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_id, $user_id); // Bind 'ss' and the user twice
$stmt->execute();

// Fetch results
$result = $stmt->get_result();
$orderItems = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderItems[] = [  // Create an array for each order
            'foodname' => $row['foodname'], 
            'quantity' => $row['quantity'], 
            'price' => $row['price']
        ];  
    }
} else {
    // Handle the case where no order is found (you might want an error message here)
}

// Close the database connection
$stmt->close();
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($orderItems);
?>

