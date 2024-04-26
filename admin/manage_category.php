<?php
//start session and database connection
session_start();
include ('../connection.php');
//check if admin logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

// redundant, delete category not needed because will affect database entities
// if (isset($_GET['id'])) {
//     $id = (int) $_GET['id']; // Sanitize ID
//     $sql = "DELETE FROM foodcat WHERE fcid = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $id);
//     $stmt->execute();
// }

// Fetch food category details
$sql = "SELECT fcid, catname FROM foodcat";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Menu</title>
    <!-- admin css  -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <?php include ('header.php'); ?>
    <h2>Categories</h2>
    <!-- add category button -->
    <div class="add-container">
        <a href="add_category.php" class="button">Add Food Category</a>
    </div>

    <!-- categories table  -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Actions</th>

                </tr>
            </thead>
            <!-- fetch categories details  -->
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- food category id  -->
                        <td>
                            <?php echo $row['fcid']; ?>
                        </td>
                        <!-- food category name  -->
                        <td>
                            <?php echo $row['catname']; ?>
                        </td>
                        <!-- edit button  -->
                        <td>
                            <a href="edit_category.php?id=<?php echo $row['fcid']; ?>" class="button">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- no food category found  -->
    <?php else: ?>
        <p>No food categories found.</p>
    <?php endif; ?>

    <script src="admin.js"></script>

</body>

</html>