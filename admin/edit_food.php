<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

include ('../connection.php');

// Check if a food ID is provided
if (!isset($_GET['id'])) {
    header('Location: edit_menu.php'); // Might want a more specific error message 
    exit();
}

$foodId = (int) $_GET['id']; // Sanitize the ID

// 1. Fetch Food Details
$sql = "SELECT * FROM food WHERE fid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $foodId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header('Location: edit_menu.php');
    exit();
}

$food = $result->fetch_assoc();

// 2. Handle Form Submission (if the form has been submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation (similar to add_item.php) 
    $foodName = $_POST['foodname'] ?? '';
    $description = $_POST['description'] ?? '';
    $fcid = $_POST['fcid'] ?? '';
    $price = $_POST['price'] ?? 0.0;

    // Image Upload/Handling
    $targetDir = "../assets/";
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if (!empty($foodName) && !empty($description) && !empty($fcid) && !empty($imageName)) {
        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Update with NEW image
                $sql = "UPDATE food SET foodname = ?, description = ?, fcid = ?, price = ?, img = ? WHERE fid = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssisd", $foodName, $description, $fcid, $price, $imageName, $foodId);

                if ($stmt->execute()) {
                    // $successMessage = "Food item updated successfully!";
                    // Redirect with JavaScript - Include the pop-up message
                    echo
                        "<script>
                        alert('Food item update successfully!');
                        window.location.href = 'edit_menu.php';
                    </script>";
                    exit();
                } else {
                    $errorMessage = "Error updating food item. Please try again.";
                }
            } else {
                $errorMessage = "Error uploading image.";
            }
        } else {
            $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else if (!empty($foodName) && !empty($description) && !empty($fcid)) {
        // Update food details WITHOUT a new image
        $sql = "UPDATE food SET foodname = ?, description = ?, fcid = ?, price = ? WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssidi", $foodName, $description, $fcid, $price, $foodId);

        if ($stmt->execute()) {
            echo
                "<script>
                        alert('Food item update successfully!');
                        window.location.href = 'edit_menu.php';
                </script>";
            exit();
        } else {
            $errorMessage = "Error updating food item. Please try again.";
        }
    } else {
        $errorMessage = "All fields are required!"; // Covers the case where no image is uploaded and fields are empty
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Food Item</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <header>
        <h1>Edit Food Details</h1>
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
            <form method="POST" action="edit_food.php?id=<?php echo $food['fid']; ?>" enctype="multipart/form-data">
                <label for="foodname">Food Name:</label>
                <input type="text" id="foodname" name="foodname"
                    value="<?php echo htmlspecialchars($food['foodname']); ?>" required><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description"
                    required><?php echo htmlspecialchars($food['description']); ?></textarea><br><br>

                <label for="fcid">Food Category:</label>
                <select id="fcid" name="fcid" required>
                    <?php
                    $sql = "SELECT fcid, catname FROM foodcat";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($row['fcid'] == $food['fcid']) ? 'selected' : ''; // Mark existing category as selected
                            echo "<option value='" . $row['fcid'] . "' " . $selected . ">" . $row['catname'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No food categories found</option>";
                    }
                    ?>
                </select><br><br>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" min="0.01" step="0.01"
                    value="<?php echo $food['price']; ?>" required><br><br>

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*"><br><br>
                <p>Current Image:
                    <?php echo $food['img']; ?>
                </p>
                <button type="submit">Update Food Item</button>
            </form>
        </section>
    </section>

</body>

</html>