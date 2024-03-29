<!DOCTYPE html>
<html lang="en">
<script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eat with JJ - Login</title>
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
        <h2>Login</h2>
        <form action="login.php" method="POST">
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
  session_start(); // Start a session for managing user login
  include ('connection.php'); // Assuming your database connection file
  
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
        $_SESSION['user_id'] = $row['uid']; // Store user ID in session
        // Redirect to a logged-in area 
        header("Location: home.html"); // Assuming you want to redirect to home
        exit();

      } else {
        echo "Incorrect password.";
      }
    } else {
      echo "Incorrect username or password.";
    }

    mysqli_close($conn); // Close the database connection 
  }
  ?>
  <footer>
    <p>&copy; Eat with JJ 2024</p>
  </footer>
</body>

</html>