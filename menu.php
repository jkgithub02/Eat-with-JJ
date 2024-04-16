<?php
// Database connection (replace with your credentials)
session_start();
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include ('connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="cart.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eat with JJ - Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include ('header.php'); ?>
    <main>
        <div class="filter-container">
            <form action="" method="post">
                <select name="category">
                    <option value='0'>All Categories</option>
                    <?php
                    $sql_categories = "SELECT * FROM foodcat";
                    $result_categories = mysqli_query($conn, $sql_categories);

                    while ($row_cat = mysqli_fetch_assoc($result_categories)) {
                        echo "<option value='" . $row_cat['fcid'] . "'>" . $row_cat['catname'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="menu-container">
            <?php
            $selected_category = isset($_POST['category']) ? (int) $_POST['category'] : '0';

            if ($selected_category == '0') {
                $sql = "SELECT * FROM food WHERE avl = 1";
                $result = mysqli_query($conn, $sql);
            
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="menu-item">
                            <img src="assets/<?= $row['img'] ?>" alt="<?= $row['foodname'] ?>">
                            <h3>
                                <?= $row['foodname'] ?>
                            </h3>
                            <p>
                                <?= $row['description'] ?>
                            </p>
                            <p class="price">RM
                                <?= $row['price'] ?>
                            </p>
                            <input type="number" min="1" value="1" class="quantity">
                            <button class="add-to-cart" data-fid="<?= $row['fid'] ?>">Add to Cart</button>
                        </div>
                    <?php }
                } else {
                    echo '<p>No food items found in the menu. </p>';
                }
                mysqli_close($conn);

            }

            if ($selected_category != '0') {
                $sql = "SELECT * FROM food f LEFT JOIN foodcat fc ON f.fcid = fc.fcid ";
                $sql .= "WHERE f.fcid = $selected_category";
                $result_filter = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result_filter) > 0) {
                    while ($row = mysqli_fetch_assoc($result_filter)) { ?>
                        <div class="menu-item">
                            <img src="assets/<?= $row['img'] ?>" alt="<?= $row['foodname'] ?>">
                            <h3>
                                <?= $row['foodname'] ?>
                            </h3>
                            <p>
                                <?= $row['description'] ?>
                            </p>
                            <p class="price">RM
                                <?= $row['price'] ?>
                            </p>
                            <input type="number" min="1" value="1" class="quantity">
                            <button class="add-to-cart" data-fid="<?= $row['fid'] ?>">Add to Cart</button>
                        </div>
                    <?php }
                } else {
                    echo '<p>No food items found in the menu. </p>';
                }
                mysqli_close($conn);
            }


            ?>
        </div>

        <footer>
            <p>&copy; Eat with JJ 2024</p>
        </footer>
    </main>
</body>

</html>