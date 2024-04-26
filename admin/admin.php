<?php
//start session and checks if admin is logged in
session_start();
//admin not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <!-- admin header  -->
    <?php include ('header.php'); ?>
    <!-- welcome message with image  -->
    <div class="welcome-message">
        <p1> 
        <img src="welcome-message.jpg" alt="Welcome Image">Welcome to the admin dashboard<br> for Eat with JJ</p1>
    </div>

</body>

</html>