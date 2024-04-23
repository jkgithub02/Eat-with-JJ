<?php
session_start();
include ('connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// 1. Fetch User Data 
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Handle the case where the user is not found (unlikely if the session is valid)
    echo "User data not found.";
    exit();
}

$user = $result->fetch_assoc();



?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src='password.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>

<body class="pagewithbg">
    <?php include ('header.php'); ?>

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
    <div class="msg">
        <p>Edit your profile</p>
    </div>
    <section class="form-wrapper">
        <section class="form-container">
            <form method="POST" action="profile.php" onsubmit="return validateProfile()">
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
                <input type="password" id="password" name="password"><br><br>

                <label for="confirmPassword">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password"><br><br>

                <button type="submit">Update Profile</button>
            </form>
        </section>
    </section>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Input Validation 
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

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
            $sql = "UPDATE user SET name = ?, email = ?, phone = ?, address = ?";

            if (!empty($newPassword)) {
                $sql .= ", password = ?"; // Add password update
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE uid = ?";
            $stmt = $conn->prepare($sql);

            if (!empty($newPassword)) {
                $stmt->bind_param("sssssi", $name, $email, $phone, $address, $hashedPassword, $userId);
            } else {

                // This is the only bind_param needed for updates without password change
                $stmt->bind_param("ssssi", $name, $email, $phone, $address, $userId);
            }

            if ($stmt->execute()) {
                echo
                "<script>
                Swal.fire({
                   icon: 'success',
                   title: 'Details updated successfully!',
                   showConfirmButton: false, 
                   timer: 1500 // Auto-close after 1.5 seconds
                }).then(() => {
                   window.location.href = 'menu.php'; // Redirect if successful
                });
          </script>";
                exit();
            } else {
                $errorMessage = "Error updating profile. Please try again.";
            }
        }
    }
    ?>

    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>
</body>

</html>