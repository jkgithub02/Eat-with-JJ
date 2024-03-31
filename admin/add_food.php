<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

include ('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation (You should add more thorough validation here)
    $foodName = $_POST['foodname'] ?? '';
    $description = $_POST['description'] ?? '';
    $fcid = $_POST['fcid'] ?? '';
    $price = $_POST['price'] ?? 0.0;

    // Image Upload
    $targetDir = "../assets/"; // Directory to store images
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if (!empty($foodName) && !empty($description) && !empty($fcid) && !empty($imageName)) {
        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Insert record into the database
                $sql = "INSERT INTO food (fcid, foodname, description, price, img)
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issds", $fcid, $foodName, $description, $price, $imageName);

                if ($stmt->execute()) {
                    // $successMessage = "Food item added successfully!";
                    // Redirect with JavaScript - Include the pop-up message
                    echo 
                    "<script>
                        alert('Food item updated successfully!');
                        window.location.href = 'edit_menu.php';
                    </script>";
                    exit();
                } else {
                    $errorMessage = "Error adding food item. Please try again.";
                }
            } else {
                $errorMessage = "Error uploading image.";
            }
        } else {
            $errorMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $errorMessage = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Food Item</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <header>
        <h1>Add Food Item</h1>
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
            <form method="POST" action="add_food.php" enctype="multipart/form-data">
                <label for="foodname">Food Name:</label>
                <input type="text" id="foodname" name="foodname" required><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br><br>

                <label for="fcid">Food Category:</label>
                <select id="fcid" name="fcid" required>
                    <?php
                    $sql = "SELECT fcid, catname FROM foodcat";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['fcid'] . "'>" . $row['catname'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No food categories found</option>";
                    }
                    ?>
                </select><br><br>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" min="0.01" step="0.01" required><br><br>

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required><br><br>

                <button type="submit">Add Food Item</button>
            </form>
        </section>
    </section>

</body>

</html>