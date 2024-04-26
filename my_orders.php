<?php 
//starts connection and session
include ('connection.php');
session_start();


// get the logged-in session's user id
$user_id = $_SESSION['user_id'];

// Preparing Orders sql statement
$sql = "SELECT o.*, f.foodname, f.price FROM orders o JOIN food f ON o.fid = f.fid WHERE o.uid = ? AND o.sid = 0"; //sid=0 indicates preparing
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); //bind user id parameter
$stmt->execute();
$resultPreparing = $stmt->get_result(); // get the result

// sql statement for completed orders
$sql = "SELECT o.*, f.foodname, f.price FROM orders o JOIN food f ON o.fid = f.fid WHERE o.uid = ? AND o.sid = 1"; //sid=1 indicates completed
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); //bind user id parameter
$stmt->execute();
$resultCompleted = $stmt->get_result(); // get the result

?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Status</title>
    <!-- css  -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="pagewithbg">
    <!-- header-->
    <?php include ('header.php'); ?>
    <!-- div for orders in preparation -->
    <div class="cartheading">
        <h1>Preparing Orders</h1>
    </div>
    <!-- get preparing orders  -->
    <?php if ($resultPreparing->num_rows > 0): ?>
        <div class="carttable">
            <!-- order table  -->
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
                    <!-- fetch order details  -->
                    <?php while ($row = $resultPreparing->fetch_assoc()): ?>
                        <tr>
                            <!-- order id  -->
                            <td>
                                <?php echo $row['oid']; ?> 
                            </td>
                            <!-- date and order time  -->
                            <td>
                                <?php echo $row['date']; ?>
                            </td>
                            <!-- foodname  -->
                            <td>
                                <?php echo $row['foodname']; ?>
                            </td>
                            <!-- quantity  -->
                            <td>
                                <?php echo $row['quantity']; ?>
                            </td>
                            <!-- price  -->
                            <td>
                                <?php echo $row['price']; ?>
                            </td>
                            <!-- show total price  -->
                            <td>
                                <?php $totalPrice = $row['quantity'] * $row['price'];
                                 echo number_format($totalPrice, 2); ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- if no preparing orders found  -->
    <?php else: ?>
        <p>No preparing orders found.</p>
    <?php endif; ?>

    <!-- completed orders section  -->
    <div class="cartheading">
        <h1>Completed</h1>
    </div>
    <!-- find completed orders  -->
    <?php if ($resultCompleted->num_rows > 0): ?>
        <div class="carttable">
            <!-- orders table  -->
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
                    <!-- fetch order details  -->
                    <?php while ($row = $resultCompleted->fetch_assoc()): ?>
                        <tr>
                            <!-- order id  -->
                            <td>
                                <?php echo $row['oid']; ?>
                            </td>
                            <!-- date and order time  -->
                            <td>
                                <?php echo $row['date']; ?>
                            </td>
                            <!-- foodname  -->
                            <td>
                                <?php echo $row['foodname']; ?>
                            </td>
                            <!-- quantity  -->
                            <td>
                                <?php echo $row['quantity']; ?>
                            </td>
                             <!-- price  -->
                            <td>
                                <?php echo $row['price']; ?>
                            </td>
                            <!-- show total price  -->
                            <td>
                                <?php $totalPrice = $row['quantity'] * $row['price'];
                                echo number_format($totalPrice, 2); ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- if no completed orders found  -->
    <?php else: ?>
        <p>No completed orders found.</p>
    <?php endif; ?>


</body>

</html>