<?php
session_start();
include ('../connection.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}



if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id']; // Sanitize ID
    $sql = "DELETE FROM user WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
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
    <?php include('header.php');?>

    <!-- <div class="add-container">
        <a href="add_food.php" class="button">View Users</a>
    </div> -->


    <?php if ($result->num_rows > 0): ?>
        <table>
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
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo $row['uid']; ?>
                        </td>
                        <td>
                            <?php echo $row['username']; ?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['email']; ?>
                        </td>
                        <td>
                            <?php echo $row['phone']; ?>
                        </td>
                        <td>
                            <?php echo $row['address']; ?>
                        </td>
                        <td>
                            <a href="edit_users.php?id=<?php echo $row['uid']; ?>" class="button">Edit</a>
                            <a href="?delete_id=<?php echo $row['uid']; ?>" class="button delete">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

        <script src="admin.js"></script>

</body>

</html>