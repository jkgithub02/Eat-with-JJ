
<header>
    <h1>Eat with JJ</h1>
    <nav>
      <a href="home.php" class="button-link">Home</a>
      <a href="menu.php" class="button-link">Menu</a>
      <a href="about.php" class="button-link">About Us</a>
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <a href="profile.php" class="button-link">Profile</a>
        <a href="logout.php" class="button-link">Logout</a>
      <?php else: ?>
        <a href="login.php" class="button-link">Login</a>
        <a href="signup.php" class="button-link">Sign Up</a>
      <?php endif; ?>
      <a href="cart.php"> <i class="fas fa-shopping-cart"></i> </a>
      </nav>
</header>