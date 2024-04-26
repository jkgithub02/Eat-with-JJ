<?php
// start the session and check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php'); // redirect to admin login page if not logged in
    exit();
}

include ('../connection.php');

// Check if a category ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_category.php'); // redirect to manage category page
    exit();
}

$categoryId = (int) $_GET['id'];

// 1. Fetch Food category Details
$sql = "SELECT * FROM foodcat WHERE fcid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $categoryId); 
$stmt->execute();
$result = $stmt->get_result();

// no food category id found
if ($result->num_rows !== 1) {
    header('Location: manage_categories.php');
    exit();
}

$category = $result->fetch_assoc();

// 2. Handle Form Submission (if the form has been submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation 
    $catName = $_POST['catname'] ?? '';
    $catName = trim($catName); // Remove unnecessary whitespace

    // if category name is left empty
    if (empty($catName)) {
        $errorMessage = "Category name cannot be empty.";
    } else {
        // Update Category 
        $sql = "UPDATE foodcat SET catname = ? WHERE fcid = ?";
        $stmt = $conn->prepare($sql);
        // update category name based on id
        $stmt->bind_param("si", $catName, $categoryId); 

        if ($stmt->execute()) {
            //sql statement successfully executed
            echo
            "<script>
                alert('Category updated successfully!');
                window.location.href = 'manage_category.php'; // Redirect to your category listing page
            </script>";
            exit();
        } else {
            $errorMessage = "Error updating category. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Food Category</title>
    <!-- admin css -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <header>
        <h1>Edit Food Category</h1>
    </header>

    <?php if (isset($successMessage)): ?>
        <p style="color: green;">
            <?php echo $successMessage; ?>
        </p>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <p style="color: red;">
            <?php echo $errorMessage; ?>
        </p>
    <?php endif; ?>
    <section class="form-wrapper">
        <section class="form-container">
            <!-- form for editing category  -->
            <form method="POST" action="edit_category.php?id=<?php echo $category['fcid']; ?>"> 
                <label for="catname">Category Name:</label>
                <!-- displays the original category name  -->
                <input type="text" id="catname" name="catname" value="<?php echo htmlspecialchars($category['catname']); ?>" required><br><br>
                <button type="submit">Update Category</button>
            </form>
        </section>
    </section>
</body>
</html>
