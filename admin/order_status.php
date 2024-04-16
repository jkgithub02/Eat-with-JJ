<?php
session_start();
include ('../connection.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}



if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id']; // Sanitize ID
    $sql = "DELETE FROM orders WHERE oid = ?";
    $stmt = $conn->prepare($sql);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = (int) $_POST['order_id'];
    $newStatusId = (int) $_POST['new_status'];

    $sql = "UPDATE orders SET sid = ? WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newStatusId, $orderId);
    if ($stmt->execute()) {
        // Redirect to refresh the page (you could add a success message as a URL parameter)
        header("Location: order_status.php");
        exit();
    } else {
        // Handle the error case (you might want to display an error message on the page)
        $errorMessage = "Error updating order status.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Status</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <h2>Preparing Orders</h2>
    <?php if ($resultPreparing->num_rows > 0): ?>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPreparing->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo $row['oid']; ?>
                        </td>
                        <td>
                            <?php echo $row['uid']; ?>
                        </td>
                        <td>
                            <?php echo $row['fid']; ?>
                        </td>
                        <td>
                            <?php echo $row['foodname']; ?>
                        </td>
                        <td>
                            <?php echo $row['quantity']; ?>
                        </td>
                        <td>
                            <?php echo $row['price']; ?>
                        </td>
                        <td>
                            <?php echo ($row['sid'] == 0) ? 'Preparing' : 'Completed'; ?>
                        </td>
                        <td>
                            <?php echo $row['date']; ?>
                        </td>
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
    <?php else: ?>
        <p>No preparing orders found.</p>
    <?php endif; ?>
    <h2>Completed</h2>
    <?php if ($resultCompleted->num_rows > 0): ?>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultCompleted->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo $row['oid']; ?>
                        </td>
                        <td>
                            <?php echo $row['uid']; ?>
                        </td>
                        <td>
                            <?php echo $row['fid']; ?>
                        </td>
                        <td>
                            <?php echo $row['foodname']; ?>
                        </td>
                        <td>
                            <?php echo $row['quantity']; ?>
                        </td>
                        <td>
                            <?php echo $row['price']; ?>
                        </td>
                        <td>
                            <?php echo ($row['sid'] == 0) ? 'Preparing' : 'Completed'; ?>
                        </td>
                        <td>
                            <?php echo $row['date']; ?>
                        </td>
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
    <?php else: ?>
        <p>No completed orders found.</p>
    <?php endif; ?>

    <script src="admin.js"></script>

</body>

</html>