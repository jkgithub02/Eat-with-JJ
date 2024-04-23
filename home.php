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
  <title>Eat with JJ - Home</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php include ('header.php'); ?>
  <main>
    <section id="hero">
      <div class="motto-container">
        <h2>"Eating with JJ is not just about the food, it's about the experience."</h2>
      </div>
    </section>

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


  </main>
  <script>
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach((carousel, index) => {
      const slides = carousel.querySelector('.carousel-slides');
      let currentSlide = 0;
      const slideWidth = carousel.querySelector('.carousel-slide').offsetWidth;

      setInterval(() => {
        currentSlide++;
        if (currentSlide >= slides.children.length) currentSlide = 0;
        slides.style.transform = `translateX(${-currentSlide * slideWidth}px)`;
      }, 3000); // Slide change every 3 seconds
    });
  </script>
  <footer>
    <p>&copy; Eat with JJ 2024</p>
  </footer>