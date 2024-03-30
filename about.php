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
    <title>Eat with JJ - About Us</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('header.php'); ?>  

    <main>
        <section id="about-hero">
            <h1>Our Story</h1>
        </section>

        <section id="mission">
            <div class="container">
                <h2>Our Mission</h2>
                <p>At Eat with JJ, we're all about sharing our love for authentic Italian cuisine. </p>
                <p>From the freshest ingredients to time-honored recipes, we strive to transport you to Italy with every
                    bite. </p>
                <p>Our mission is to create a warm and inviting atmosphere where you can savor delicious food, make
                    memories, and feel like part of our family.</p>
            </div>
        </section>

        <section id="team">
            <div class="team-container">
                <h2>Meet the Team</h2>
                <br>
                <div class="team-profiles">
                    <div class="profile">
                        <img src="assets/ilt.jpeg" alt="Head Chef">
                        <h3>Head Chef</h3>
                        <p>Short bio about the chef's experience.</p>
                    </div>
                    <div class="profile">
                        <img src="assets/ilt.jpeg" alt="Head Chef">
                        <h3>Head Chef</h3>
                        <p>Short bio about the chef's experience.</p>
                    </div>
                    <div class="profile">
                        <img src="assets/ilt.jpeg" alt="Head Chef">
                        <h3>Head Chef</h3>
                        <p>Short bio about the chef's experience.</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="values-wrapper">
            <section id="values">
                <div class="container">
                    <h2>Our Values</h2>
                    <p>We source the freshest, seasonal ingredients, supporting local farmers whenever possible.</p>
                    <p>We strive to create a friendly and welcoming atmosphere for every guest, ensuring a memorable
                        dining experience.</p>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; Eat with JJ 2024</p>
    </footer>

</body>

</html>