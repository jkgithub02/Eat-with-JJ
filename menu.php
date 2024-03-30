<?php
// Database connection (replace with your credentials)
session_start();
// Enable error reporting for development
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<script src="https://kit.fontawesome.com/8e05c53646.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="scripts.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eat with JJ - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main>
        <div class="menu-container">
            <?php
            $sql = "SELECT * FROM food";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?> 
                    <div class="menu-item">
                        <img src="assets/<?= $row['img'] ?>" alt="<?= $row['foodname'] ?>">
                        <h3><?= $row['foodname'] ?></h3>
                        <p><?= $row['description'] ?></p>
                        <p class="price">RM<?= $row['price'] ?></p>
                        <input type="number" min="1" value="1" class="quantity">
                        <button class="add-to-cart" data-fid="<?= $row['fid'] ?>">Add to Cart</button>
                    </div>
               <?php } 
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
</body>
</html>
