<?php
// Database connection (replace with your connection details)
session_start();
// error_reporting(0);
include ('connection.php');
?>

<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eat with JJ - Login</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <script src="login.js"></script>
</head>

<body class="pagewithbg">
  <?php include ('header.php'); ?>
  <main>
    <section class="form-wrapper">
      <section class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="POST" id="loginForm">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
          <button type="submit">Login</button>
        </form>
      </section>
    </section>
  </main>

  <?php

  if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query the database for the user
    $sql = "SELECT uid, password FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
      // User was found, now verify the password
      $row = mysqli_fetch_assoc($result);

      if (password_verify($password, $row['password'])) {
        // Successful login 
        $_SESSION['logged_in'] = true;  // Use the correct flag name
        $_SESSION['user_id'] = $row['uid']; // Store user ID in session
        $_SESSION['user_logged_in'] = true;
        echo '1';
        // Redirect to a logged-in area 
        header("Location: menu.php"); // Assuming you want to redirect to home
        exit();

      } else {
        echo "Incorrect Username or Password";
      }
    } else {
      echo "Incorrect Username or Password";
    }

    mysqli_close($conn); // Close the database connection 
  }
  ?>
  <footer>
    <p>&copy; Eat with JJ 2024</p>
  </footer>
</body>

</html>