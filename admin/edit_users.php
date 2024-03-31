<?php
session_start();
include ('../connection.php');

// Access Control: Only allow logged-in admins
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}


$userId = (int) $_GET['id'];

if (!$userId) {
    header('Location: manage_users.php');
    exit();
}

// 1. Fetch User Data 
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "User data not found.";
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation 
    $username = $_POST['username'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $errors = [];

    // ... (Add validation checks for name, email, phone, address) ... 

    // Password-Specific Validation
    if (!empty($newPassword)) {
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
    }

    // Update Logic (If validation passed)
    if (empty($errors)) {
        $sql = "UPDATE user SET username = ?, name = ?, email = ?, phone = ?, address = ?"; // Adapt column names

        if (!empty($newPassword)) {
            $sql .= ", password = ?"; // Add password update
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE uid = ?";
        $stmt = $conn->prepare($sql);

        if (!empty($newPassword)) {
            $stmt->bind_param('ssssssi', $username, $name, $email, $phone, $address, $hashedPassword, $userId);
        } else {

            // This is the only bind_param needed for updates without password change
            $stmt->bind_param("sssssi",$username, $name, $email, $phone, $address, $userId);
        }

        if ($stmt->execute()) {
            echo "<script>
                alert('User details updated successfully!');
                window.location.href = 'manage_users.php'; 
              </script>";
            exit();
        } else {
            $errorMessage = "Error updating user profile. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <header>
        <h1>Edit User Details</h1>
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
            <form method="POST" action="edit_users.php?id=<?php echo $user['uid']; ?>">

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                    required><br><br>

                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                    required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required><br><br>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone"
                    value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>

                <label for="address">Address:</label>
                <textarea id="address"
                    name="address"><?php echo htmlspecialchars($user['address']); ?></textarea><br><br>

                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword"><br><br>

                <label for="confirmPassword">Confirm New Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword"><br><br>

                <button type="submit">Update Profile</button>
            </form>
        </section>
    </section>

</body>

</html>