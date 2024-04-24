<?php //original orders page
include ('connection.php');
session_start();


// Assuming you have a way to get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Preparing Orders
$sql = "SELECT o.*, f.foodname, f.price FROM orders o JOIN food f ON o.fid = f.fid WHERE o.uid = ? AND o.sid = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$resultPreparing = $stmt->get_result(); // Get the result

// Completed Orders
$sql = "SELECT o.*, f.foodname, f.price FROM orders o JOIN food f ON o.fid = f.fid WHERE o.uid = ? AND o.sid = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$resultCompleted = $stmt->get_result(); // Get the result

?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Status</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="pagewithbg">
    <?php include ('header.php'); ?>
    <div class="cartheading">
        <h1>Preparing Orders</h1>
    </div>
    <?php if ($resultPreparing->num_rows > 0): ?>
        <div class="carttable">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Time</th>
                        <th>Food ordered</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultPreparing->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row['oid']; ?>
                            </td>
                            <td>
                                <?php echo $row['date']; ?>
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
                                <?php $totalPrice = $row['quantity'] * $row['price'];
                                 echo number_format($totalPrice, 2); ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No preparing orders found.</p>
    <?php endif; ?>
    <div class="cartheading">
        <h1>Completed</h1>
    </div>
    <?php if ($resultCompleted->num_rows > 0): ?>
        <div class="carttable">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Time</th>
                        <th>Food ordered</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultCompleted->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row['oid']; ?>
                            </td>
                            <td>
                                <?php echo $row['date']; ?>
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
                                <?php $totalPrice = $row['quantity'] * $row['price'];
                                echo number_format($totalPrice, 2); ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No completed orders found.</p>
    <?php endif; ?>


</body>

</html>