<?php
session_start();
include ('../connection.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}



if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id']; // Sanitize ID
    $sql = "DELETE FROM food WHERE fid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

// Fetch food items 
$sql = "SELECT fid, fcid, foodname, price, description, avl FROM food";
$result = $conn->query($sql);

if (isset($_GET['toggle_availability'])) {
    $id = (int) $_GET['toggle_availability']; 
    $sql = "UPDATE food SET avl = (avl ^ 1)  WHERE fid = ?"; // Toggle with XOR
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: edit_menu.php'); //Redirect to refresh
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Menu</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <h2>Menu</h2>
    <div class="add-container">
        <a href="add_food.php" class="button">Add Food Item</a>
    </div>


    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Food Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo $row['fid']; ?>
                        </td>
                        <td>
                            <?php echo $row['fcid']; ?>
                        </td>
                        <td>
                            <?php echo $row['foodname']; ?>
                        </td>
                        <td>RM
                            <?php echo $row['price']; ?>
                        </td>
                        <td>
                            <?php echo $row['description']; ?>
                        </td>
                        <td>
                            <?php echo ($row['avl'] == 1) ? 'Available' : 'Unavailable'; ?>
                        </td>
                        <td>
                            <a href="edit_food.php?id=<?php echo $row['fid']; ?>" class="button">Edit</a>
                            <a href="?delete_id=<?php echo $row['fid']; ?>" class="button delete">Delete</a>
                            <a href="?toggle_availability=<?php echo $row['fid']; ?>" class="button availability">
                            <?php echo ($row['avl'] == 1) ? 'Set Unavailable' : 'Set Available'; ?> 
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No food items found.</p>
    <?php endif; ?>

    <script src="admin.js"></script>

</body>

</html>