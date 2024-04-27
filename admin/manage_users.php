<?php
// start the session and checks if admin is logged in
session_start();
include ('../connection.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

// delete button function
if (isset($_GET['delete_id'])) {
    $userId = (int) $_GET['delete_id'];

    // delete orders related to the user first
    $deleteOrdersSql = "DELETE FROM orders WHERE uid = ?";
    $deleteOrdersStmt = $conn->prepare($deleteOrdersSql);
    $deleteOrdersStmt->bind_param("i", $userId);
    $deleteOrdersStmt->execute();
    $deleteOrdersStmt->close();

    // Now proceed with deleting the user
    $deleteUserSql = "DELETE FROM user WHERE uid = ?";
    $deleteUserStmt = $conn->prepare($deleteUserSql);
    $deleteUserStmt->bind_param("i", $userId);
    $deleteUserStmt->execute();
    $deleteUserStmt->close();
}

// Fetch food items 
$sql = "SELECT uid, username, name, email, phone, address FROM user";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>View Users</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <h2>Users</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <!-- table columns  -->
            <thead>
                <tr>
                    <th>UID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- fetch user details in database  -->
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- user id  -->
                        <td>
                            <?php echo $row['uid']; ?>
                        </td>
                        <!-- username -->
                        <td>
                            <?php echo $row['username']; ?>
                        </td>
                        <!-- user full name -->
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <!-- user email -->
                        <td>
                            <?php echo $row['email']; ?>
                        </td>
                        <!-- user phone number -->
                        <td>
                            <?php echo $row['phone']; ?>
                        </td>
                        <!-- user address -->
                        <td>
                            <?php echo $row['address']; ?>
                        </td>
                        <!-- edit user and delete user buttons -->
                        <td>
                            <a href="edit_users.php?id=<?php echo $row['uid']; ?>" class="button">Edit</a>
                            <a href="?delete_id=<?php echo $row['uid']; ?>" class="button delete">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- if no users are found -->
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <!-- javascript for admins  -->
    <script src="admin.js"></script>

</body>

</html>