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
?>


<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <!-- admin css  -->
    <link rel="stylesheet" href="admin.css">
    <script src='admin.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
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
            <!-- edit user details form  -->
            <form method="POST" action="edit_users.php?id=<?php echo $user['uid']; ?>"
                onsubmit="return validateProfile()">
                <!-- autofill user details -->
                <!-- username  -->
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

                <!-- user full name  -->
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                    required><br><br>

                <!-- user email  -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required><br><br>

                <!-- user phone number  -->
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone"
                    value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>

                <!-- user address  -->
                <label for="address">Address:</label>
                <textarea id="address"
                    name="address"><?php echo htmlspecialchars($user['address']); ?></textarea><br><br>

                <!-- password fields  -->
                <label for="newPassword">New Password:</label>
                <input type="password" id="password" name="password"><br><br>

                <label for="confirmPassword">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password"><br><br>

                <!-- update profile button  -->
                <button type="submit">Update Profile</button>
            </form>
        </section>
    </section>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Input Validation 
        $username = $_POST['username'] ?? ''; //get username
        $name = $_POST['name'] ?? ''; //get name
        $email = $_POST['email'] ?? ''; //get email
        $phone = $_POST['phone'] ?? ''; //get phone number
        $address = $_POST['address'] ?? ''; //get address
        $newPassword = $_POST['password'] ?? ''; //get password
        $confirmPassword = $_POST['confirm_password'] ?? ''; //get password confirmation text

        $errors = [];
        //find usernmame and email
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ? AND uid <> ?");
        $stmt->bind_param("si", $username, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_row()[0];

        if ($count > 0) { // Duplicate username found 
            // sweet alert to inform duplicate username
            echo "<script>
              Swal.fire({
                 icon: 'error',
                 title: 'Username Unavailable',
                 text: 'The chosen username is already taken. Please select a different one.'
              });
              </script>";
            exit();
        }

        //email validation to ensure duplicates
        $newEmail = $_POST['email'] ?? '';
        //if duplicate email is not found
        if ($newEmail !== $user['email']) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
            $stmt->bind_param("s", $newEmail);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_row()[0];

            if ($count > 0) { // Duplicate email found
                //sweet alert to inform duplicate
                echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Email Unavailable',
                text: 'The chosen email is already in use. Please select a different one.'
            });
            </script>";
                exit();
            }
        }


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

            //append user id
            $sql .= " WHERE uid = ?";
            $stmt = $conn->prepare($sql);

            if (!empty($newPassword)) {
                //update with new password
                $stmt->bind_param('ssssssi', $username, $name, $email, $phone, $address, $hashedPassword, $userId);
            } else {

                // This is the only bind_param needed for updates without password change
                $stmt->bind_param("sssssi", $username, $name, $email, $phone, $address, $userId);
            }

            if ($stmt->execute()) {
                //sweetalert if sql statement executed successfully
                echo
                    "<script>
            Swal.fire({
               icon: 'success',
               title: 'Details updated successfully!',
               showConfirmButton: false, 
               timer: 1500 // Auto-close after 1.5 seconds
            }).then(() => {
               window.location.href = 'manage_users.php'; // Redirect if successful
            });
      </script>";
                exit();
            } else {
                $errorMessage = "Error updating user profile. Please try again.";
            }
        }
    }

    ?>

</body>
</html>