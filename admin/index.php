<?php
session_start();

// Database Connection 
include ('../connection.php'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch Admin from Database
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Password Verification
        if (password_verify($password, $admin['password'])) {
            // Successful admin login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['aid']; // Store admin ID
            // Redirect to the admin dashboard
            header('Location: admin.php');
            exit();
        } else {
            // Invalid password
            $errorMessage = "Invalid username or password.";
        }
    } else {
        // User not found
        $errorMessage = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <!-- css for admin  -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <main>
        <section class="form-wrapper">
            <section class="form-container">
                <h1>Admin Login</h1>
                <?php if (isset($errorMessage)): ?>
                    <p style="color: red;">
                        <?php echo $errorMessage; ?>
                    </p>
                <?php endif; ?>
                <!-- admin login form  -->
                <form method="POST">
                    <label for="username">Admin Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
    
                    <button type="submit">Login</button>
                </form>
            </section>
        </section>
    </main>
</body>

</html>