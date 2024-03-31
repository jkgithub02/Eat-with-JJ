<?php
session_start();
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
    <?php include ('header.php'); ?>
    <div class="welcome-message">
        <p1> 
        <img src="welcome-message.jpg" alt="Welcome Image">Welcome to the admin dashboard<br> for Eat with JJ</p1>
    </div>

</body>

</html>