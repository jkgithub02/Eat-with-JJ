<!DOCTYPE html>
<html lang="en">
<script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eat with JJ - Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Eat with JJ</h1>
    <nav>
      <a href="home.html" class="button-link">Home</a>
      <a href="menu.html" class="button-link">Menu</a>
      <a href="about.php" class="button-link">About Us</a>
      <a href="login.php" class="button-link">Login</a>
      <a href="signup.php" class="button-link">Sign Up</a>
      <a href="cart.php"> <i class="fas fa-shopping-cart"></i> </a>
      </nav>
  </header>
  <main>
    <section class="form-wrapper"> 
      <section class="form-container">
        <h2>Sign Up</h2>
        
        <form method="POST" action="signup.php">
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
        // Database connection (replace with your connection details)
        session_start();
        // error_reporting(0);
        include('connection.php'); 

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

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                echo "Signup successful! You can now log in."; 
            } else {
                echo "Error during signup: " . mysqli_error($conn);
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


