<?php 
    include('header.php');   
    session_start();
    include ('connection.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<<<<<<< HEAD
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eat with JJ - Menu</title>
  <link rel="stylesheet" href="style.css"> 
=======
<script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eat with JJ - Home</title>
    <link rel="stylesheet" href="style.css">
>>>>>>> ebf9b10c6409d1c7bff73c277e529bf1ec5e34bd
</head>

<body>
<<<<<<< HEAD
  <main>
  <div id="menu-items">
    <?php if (isset($_SESSION['user_id'])) : ?>
      <?php
      $sql = "SELECT * FROM food"; 
      $result = $conn->query($sql);
      $menuItems = $result->fetch_all(MYSQLI_ASSOC); 
      ?>
      <?php foreach($menuItems as $item) : ?> 
        <div class="menu-item">
          <?php if ($item['img']) : ?> 
            <img src="<?= $item['img']; ?>" alt="<?= $item['foodname']; ?>">
          <?php endif; ?> 
          <h3><?= $item['foodname']; ?></h3>
          <p><?= $item['description']; ?></p>
          <p class="price">$<?= $item['price']; ?></p>
          <input type="number" class="quantity-input" value="1" min="1">
          <button class="add-to-cart" data-item-id="<?= $item['fid'] ?>">Add to Cart</button>
        </div>
      <?php endforeach; ?> 
    <?php else: ?>
      <p>Please <a href="login.php">login</a> to add items to your cart.</p>
    <?php endif; ?>
  </div>

  </main>

  <?php mysqli_close($conn); ?>

  <script src="scripts.js"></script> </body>
</html>
=======
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
            // Database connection (replace with your credentials)
            include ('connection.php');

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
>>>>>>> ebf9b10c6409d1c7bff73c277e529bf1ec5e34bd
