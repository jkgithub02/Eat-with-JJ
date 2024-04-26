<header>
  <!-- logo image and title  -->
  <div class="logo-and-title"> <img src="websiteimg/logo.jpg" alt="Eat with JJ Logo" id="logo-image">
    <h1>Eat with JJ</h1>
  </div>
  <!-- javascript link  -->
  <script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>
  <!-- navigation bar  -->
  <nav>
    <a href="home.php" class="button-link">Home</a>
    <a href="menu.php" class="button-link">Menu</a>
    <a href="about.php" class="button-link">About Us</a>
    <!-- only show these buttons if user is logged in  -->
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
      <a href="profile.php" class="button-link">Profile</a>
      <a href="my_orders.php" class="button-link">My Orders</a>
      <a href="logout.php" class="button-link">Logout</a>
      <!-- show these only if user is not logged in  -->
    <?php else: ?>
      <a href="login.php" class="button-link">Login</a>
      <a href="signup.php" class="button-link">Sign Up</a>
    <?php endif; ?>
    <!-- show cart icon  -->
    <a href="cart.php"> <i class="fas fa-shopping-cart"></i> </a>
  </nav>
</header>