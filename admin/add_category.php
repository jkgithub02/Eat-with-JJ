<?php
//start the session and check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

//database connection
include ('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation
    $foodcatName = $_POST['catname'] ?? '';

    //takes the new food category name and inserts into the database
    if (!empty($foodcatName)) {
        $sql = "INSERT INTO foodcat (fcid, catname)
                        VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $fcid, $foodcatName); //bind food category id and 

        if ($stmt->execute()) {
            // redirect with JavaScript - Include the pop-up message
            echo
                "<script>
                        alert('Food category updated successfully!');
                        window.location.href = 'manage_category.php';
                    </script>";
            exit();
        } else {
            $errorMessage = "Error adding food category. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Food Category</title>
    <!-- css for admin  -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <!-- header  -->
    <header>
        <h1>Add Food Category</h1>
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
            <!-- form to add food category  -->
            <form method="POST" action="add_category.php">
                <label for="catname">Category Name:</label>
                <input type="text" id="catname" name="catname" required><br><br>
                <button type="submit">Add Food Category</button>
            </form>
        </section>
    </section>

</body>

</html>