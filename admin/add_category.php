<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

include ('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation (You should add more thorough validation here)
    $foodcatName = $_POST['catname'] ?? '';

    if (!empty($foodcatName)) {

        $sql = "INSERT INTO foodcat (fcid, catname)
                        VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $fcid, $foodcatName);

        if ($stmt->execute()) {
            // Redirect with JavaScript - Include the pop-up message
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
    <link rel="stylesheet" href="admin.css">
</head>

<body>
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
            <form method="POST" action="add_category.php">
                <label for="catname">Category Name:</label>
                <input type="text" id="catname" name="catname" required><br><br>
                <button type="submit">Add Food Category</button>
            </form>
        </section>
    </section>

</body>

</html>