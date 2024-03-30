<?php
 // Database connection (replace with your connection details)
 session_start();
  // error_reporting(0);
  include ('connection.php');
 ?>
<!DOCTYPE html>
<html lang="en">
<script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eat with JJ - Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Eat with JJ</h1>
        <nav>
            <a href="home.php" class="button-link">Home</a>
            <a href="menu.php" class="button-link">Menu</a>
            <a href="about.php" class="button-link">About Us</a>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="logout.php" class="button-link">Logout</a>
            <?php else: ?>
                <a href="login.php" class="button-link">Login</a>
                <a href="signup.php" class="button-link">Sign Up</a>
            <?php endif; ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> </a>
        </nav>

    </header>
    <main>
        <div class="menu-container">
            <?php

            // Fetch all food items (you might want to modify this later for filtering)
            $sql = "SELECT * FROM food";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Iterate through each food item
                while ($row = mysqli_fetch_assoc($result)) {

                    echo '<div class="menu-item">';
                    echo '<img src="assets/' . $row['img'] . '" alt="' . $row['foodname'] . '" >';
                    echo '<h3>' . $row['foodname'] . '</h3>';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '<p class="price">RM' . $row['price'] . '</p>';
                    echo '<input type="number" min="1" value="1" class="quantity">';
                    echo '<button class="add-to-cart" data-fid="' . $row['fid'] . '">Add to Cart</button>';
                    echo '</div>';

                }
            } else {
                echo '<p>No food items found in the menu. </p>';
            }

            mysqli_close($conn);
            ?>
        </div>

        <footer>
            <p>&copy; Eat with JJ 2024</p>
        </footer>
    </main>

    <script src="scripts.js"></script>

</body>

</html>