<?php
// Database connection (replace with your connection details)
session_start();
include ('connection.php');
?>

<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eat with JJ - Login</title>
  <!-- css  -->
  <link rel="stylesheet" href="style.css">
  <!-- javascript links and files  -->
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
        <!-- login form  -->
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
  //if username and password are submitted
  if (isset($_POST['username']) && isset($_POST['password'])) {

    //sanitize the input to prevent SQL injection attacks
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query the database for the user
    //find userid and password according to username
    $sql = "SELECT uid, password FROM user WHERE username = ?";
    //prepare the statement
    $stmt = mysqli_prepare($conn, $sql);
    //bind username parameter
    mysqli_stmt_bind_param($stmt, "s", $username);
    //execute statement
    mysqli_stmt_execute($stmt);
    //get result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
      // User was found, now verify the password
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row['password'])) {
        // Successful login 
        $_SESSION['logged_in'] = true;  // Use the correct flag name
        $_SESSION['user_id'] = $row['uid']; // Store user ID in session
        $_SESSION['user_logged_in'] = true;
        //sweet alert for successful login
        echo 
          "<script>
        Swal.fire({
           icon: 'success',
           title: 'Login Successful!',
           showConfirmButton: false, 
           timer: 1500 // Auto-close after 1.5 seconds
        }).then(() => {
           window.location.href = 'menu.php'; // Redirect if successful
        });
  </script>";
        exit();

      } else {
        //sweet alert for invalid login
        echo "<script>                             
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Incorrect Username or Password'
      });
      </script>";

      }
    } else {
      //sweet alert for invalid login
      echo "<script>                             
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Incorrect Username or Password'
    });
    </script>";
    }

    mysqli_close($conn); // Close the database connection 
  }
  ?>

  <!-- footer  -->
  <footer>
    <p>&copy; Eat with JJ 2024</p>
  </footer>
</body>

</html>