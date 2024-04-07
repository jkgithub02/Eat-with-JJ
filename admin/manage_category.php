<?php
session_start();
include ('../connection.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}



if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Sanitize ID
    $sql = "DELETE FROM foodcat WHERE fcid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

// Fetch food category details
$sql = "SELECT fcid, catname FROM foodcat";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Menu</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <h2>Categories</h2>
    <div class="add-container">
        <a href="add_category.php" class="button">Add Food Category</a>
    </div>


    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Actions</th>

                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo $row['fcid']; ?>
                        </td>
                        <td>
                            <?php echo $row['catname']; ?>
                        </td>

                        <td>
                            <a href="edit_category.php?id=<?php echo $row['fcid']; ?>" class="button">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No food categories found.</p>
    <?php endif; ?>

    <script src="admin.js"></script>

</body>

</html>