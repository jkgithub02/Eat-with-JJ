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
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eat with JJ - Menu</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
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
