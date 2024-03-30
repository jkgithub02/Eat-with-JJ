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
    <section id="hero">
      <div class="motto-container">
        <h2>"Eating with JJ is not just about the food, it's about the experience."</h2>
      </div>
      <div class="image-container">
        <img src="assets/lobster.jpeg" alt="lobster pasta">
      </div>
    </section>

    <div class="testimonials-wrapper">
      <section id="testimonials" class="testimonials">
        <h2>What Our Customers Say</h2>
        <div class="testimonials-container">
          <div class="testimonial">
            <p>"Amazing food and excellent service. My new favorite restaurant!" - Jane Doe</p>
          </div>
          <div class="testimonial">
            <p>"The freshest ingredients and creative dishes. Highly recommend!" - John Smith</p>
          </div>
        </div>
      </section>
    </div>

    <div class="heading-container">
      <h3>Have a look through our Chef's recommended dishes!</h3>
    </div>
    <div class="carousels-container">
      <div class="carousel">
        <ul class="carousel-slides">
          <li class="carousel-slide"><img src="assets/fish_burger.jpeg" alt="Image 1"></li>
          <li class="carousel-slide"><img src="assets/juice.jpeg" alt="Image 2"></li>
          <li class="carousel-slide"><img src="assets/hawaiian.jpeg" alt="Image 3"></li>
        </ul>
      </div>
      <div class="carousel">
        <ul class="carousel-slides">
          <li class="carousel-slide"><img src="assets/gc_burger.jpeg" alt="Image 1"></li>
          <li class="carousel-slide"><img src="assets/hibtea.jpeg" alt="Image 2"></li>
          <li class="carousel-slide"><img src="assets/aglioolio.jpeg" alt="Image 3"></li>
        </ul>
      </div>
    </div>

  </main>
  <footer>
    <p>&copy; Eat with JJ 2024</p>
  </footer>