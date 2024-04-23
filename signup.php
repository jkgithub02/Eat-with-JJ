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
  <title>Eat with JJ - Sign Up</title>
  <link rel="stylesheet" href="style.css">
  <script src='password.js'></script>
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>

<body class="pagewithbg">
  <?php include ('header.php'); ?>
  <main>
    <section class="form-wrapper">
      <section class="form-container">
        <h2>Sign Up</h2>

        <form method="POST" action="signup.php" onsubmit="return validatePassword()">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required>
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" name="phone" required>
          <label for="address">Address:</label>
          <input type="text" id="address" name="address" required>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
          <button type="submit">Sign Up</button>
        </form>



      </section>
    </section>
  </main>

  <?php



  // Form Submission Check
  if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['address']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['username'])) {

    // Collect and Sanitize Input Data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ? AND uid <> ?");
    $stmt->bind_param("si", $username, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];

    if ($count > 0) { // Duplicate username found 
        echo "<script>
              Swal.fire({
                 icon: 'error',
                 title: 'Username Unavailable',
                 text: 'The chosen username is already taken. Please select a different one.'
              });
              </script>"; 
        exit(); 
    }

    // Check for Existing Email and Username
    $query = "SELECT * FROM user WHERE email = ? OR username = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
      die("Error preparing SQL statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ss", $email, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An account with that email or username already exists.'
            });
          </script>";
      exit();
    }


    // Password Matching:
    if ($password !== $confirm_password) {
      echo "Passwords do not match. Please try again.";
      exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL Insert statement
    $sql = "INSERT INTO user (username, name, phone, address, email, password) VALUES (?, ?, ?, ?, ?, ?)";

    // Prepared statement for security
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
      die("Error preparing SQL statement: " . mysqli_error($conn));
    }

    // Bind parameters ('ssss' indicates string types)
    mysqli_stmt_bind_param($stmt, "ssssss", $username, $name, $phone, $address, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
      echo "Signup successful! You can now log in.";
    } else {
      // Check for specific error code related to duplicates
      if (mysqli_errno($conn) === 1062) { // MySQL error code for duplicates
        $error_message = "An account with that email or username already exists.";
      } else {
        $error_message = "Error during signup: " . mysqli_error($conn);
      }

      // Display user-friendly error message (more on this below)
      echo $error_message;
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
  }
  ?>


  <footer>
    <p>&copy; Eat with JJ 2024</p>
  </footer>
</body>

</html>