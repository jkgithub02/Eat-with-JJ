<?php
//starts connection and session
session_start();
include ('../connection.php');
//check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}


//delete button function
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id']; // Sanitize ID
    //delete order from orders table
    $sql = "DELETE FROM orders WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    //bind the order id
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

// Fetch Preparing Orders
$sql = "SELECT o.oid, o.uid, o.fid, f.foodname, o.quantity,  f.price, o.sid, o.date
        FROM orders o 
        JOIN food f ON o.fid = f.fid 
        WHERE o.sid = 0";
$resultPreparing = $conn->query($sql);

// Fetch Completed Orders
$sql = "SELECT o.oid, o.uid, o.fid, f.foodname, o.quantity,  f.price, o.sid, o.date
        FROM orders o 
        JOIN food f ON o.fid = f.fid 
        WHERE o.sid = 1";
$resultCompleted = $conn->query($sql);


//updates the order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    //order id
    $orderId = (int) $_POST['order_id'];
    $newStatusId = (int) $_POST['new_status'];

    //set the order status as 'prepared' 0 or 'completed' 1
    $sql = "UPDATE orders SET sid = ? WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newStatusId, $orderId);
    if ($stmt->execute()) {
        // Redirect to refresh the page
        header("Location: order_status.php");
        exit();
    } else {
        // Handle the error case 
        $errorMessage = "Error updating order status.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Status</title>
    <!-- admin css  -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <!-- header  -->
    <?php include ('header.php'); ?>
    <!-- div for orders in preparation -->
    <h2>Preparing Orders</h2>
    <!-- get preparing orders  -->
    <?php if ($resultPreparing->num_rows > 0): ?>
        <!-- order table  -->
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Food ID</th>
                    <th>Food ordered</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- fetch order details  -->
                <?php while ($row = $resultPreparing->fetch_assoc()): ?>
                    <tr>
                        <!-- order id  -->
                        <td>
                            <?php echo $row['oid']; ?>
                        </td>
                        <!-- user id  -->
                        <td>
                            <?php echo $row['uid']; ?>
                        </td>
                        <!-- food id  -->
                        <td>
                            <?php echo $row['fid']; ?>
                        </td>
                        <!-- food name  -->
                        <td>
                            <?php echo $row['foodname']; ?>
                        </td>
                        <!-- food quantity  -->
                        <td>
                            <?php echo $row['quantity']; ?>
                        </td>
                        <!-- price  -->
                        <td>
                            <?php echo $row['price']; ?>
                        </td>
                        <!-- show order status  -->
                        <td>
                            <?php echo ($row['sid'] == 0) ? 'Preparing' : 'Completed'; ?>
                        </td>
                        <!-- show date and order time  -->
                        <td>
                            <?php echo $row['date']; ?>
                        </td>
                        <!-- show total price  -->
                        <td>
                            <?php $totalPrice = $row['quantity'] * $row['price'];
                            echo number_format($totalPrice, 2); ?>
                        </td>
                        <!-- display buttons  -->
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['oid']; ?>">
                                <?php if ($row['sid'] == 1): ?>
                                    <button type="submit" name="new_status" value="0" class="button">Mark as Preparing</button>
                                <?php else: ?>
                                    <button type="submit" name="new_status" value="1" class="button">Mark as Completed</button>
                                <?php endif; ?>
                                <a href="?delete_id=<?php echo $row['oid']; ?>" class="button delete">Delete</a>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- if no preparing orders found  -->
    <?php else: ?>
        <p>No preparing orders found.</p>
    <?php endif; ?>

    <!-- completed orders section  -->
    <h2>Completed</h2>
    <!-- find completed orders  -->
    <?php if ($resultCompleted->num_rows > 0): ?>
        <!-- orders table  -->
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Food ID</th>
                    <th>Food ordered</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- fetch order details  -->
                <?php while ($row = $resultCompleted->fetch_assoc()): ?>
                    <tr>
                        <!-- order id  -->
                        <td>
                            <?php echo $row['oid']; ?>
                        </td>
                        <!-- user id  -->
                        <td>
                            <?php echo $row['uid']; ?>
                        </td>
                        <!-- food id  -->
                        <td>
                            <?php echo $row['fid']; ?>
                        </td>
                        <!-- food name  -->
                        <td>
                            <?php echo $row['foodname']; ?>
                        </td>
                        <!-- food quantity  -->
                        <td>
                            <?php echo $row['quantity']; ?>
                        </td>
                        <!-- price  -->
                        <td>
                            <?php echo $row['price']; ?>
                        </td>
                         <!-- show order status  -->
                        <td>
                            <?php echo ($row['sid'] == 0) ? 'Preparing' : 'Completed'; ?>
                        </td>
                        <!-- show date and order time  -->
                        <td>
                            <?php echo $row['date']; ?>
                        </td>
                        <!-- show total price  -->
                        <td>
                            <?php $totalPrice = $row['quantity'] * $row['price'];
                            echo number_format($totalPrice, 2); ?>
                        </td>
                        <!-- display buttons  -->
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['oid']; ?>">
                                <?php if ($row['sid'] == 1): ?>
                                    <button type="submit" name="new_status" value="0" class="button">Mark as Preparing</button>
                                <?php else: ?>
                                    <button type="submit" name="new_status" value="1" class="button">Mark as Completed</button>
                                <?php endif; ?>
                                <a href="?delete_id=<?php echo $row['oid']; ?>" class="button delete">Delete</a>

                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- if no completed orders found  -->
    <?php else: ?>
        <p>No completed orders found.</p>
    <?php endif; ?>

    <!-- javascript for admin -->
    <script src="admin.js"></script>

</body>

</html>