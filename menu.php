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
<!-- javascript links  -->
<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="cart.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eat with JJ - Home</title>
    <!-- css  -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="menupage">
    <!-- header  -->
    <?php include ('header.php'); ?>
    <main>
        <div class="filter-container">
            <!-- filter function as a form with button  -->
            <form action="" method="post">
                <select name="category">
                    <option value='0'>All Categories</option>
                    <?php
                    // sql statement to get foodcategories name 
                    $sql_categories = "SELECT * FROM foodcat";
                    $result_categories = mysqli_query($conn, $sql_categories);

                    // use loop to get food category id and food category name 
                    while ($row_cat = mysqli_fetch_assoc($result_categories)) {
                        echo "<option value='" . $row_cat['fcid'] . "'>" . $row_cat['catname'] . "</option>";
                    }
                    ?>
                </select>
                <!-- filter button  -->
                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="menu-container">
            <?php
            $selected_category = isset($_POST['category']) ? (int) $_POST['category'] : '0';
            //category 0 means all food items
            if ($selected_category == '0') {
                //only select available food (availability can be edited from admin side)
                $sql = "SELECT * FROM food WHERE avl = 1"; //avl =1 is available 
                $result = mysqli_query($conn, $sql);
                
                //show all food items and details using while loop
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="menu-item">
                            <!-- image  -->
                            <img src="assets/<?= $row['img'] ?>" alt="<?= $row['foodname'] ?>">
                            <!-- food name  -->
                            <h3>
                                <?= $row['foodname'] ?>
                            </h3>
                            <!-- food description  -->
                            <p>
                                <?= $row['description'] ?>
                            </p>
                            <!-- food price  -->
                            <p class="price">RM
                                <?= $row['price'] ?>
                            </p>
                            <!-- set minimum value as 1 and default value as 1  -->
                            <input type="number" min="1" value="1" class="quantity">
                            <!-- add to cart button  -->
                            <button class="add-to-cart" data-fid="<?= $row['fid'] ?>">Add to Cart</button>
                        </div>
                    <?php }
                } else {
                    //display message if no food was found
                    echo '<p>No food items found in the menu. </p>';
                }
                mysqli_close($conn);

            }

            //if selected category is not 0 (all categories)
            if ($selected_category != '0') {
                $sql = "SELECT * FROM food f LEFT JOIN foodcat fc ON f.fcid = fc.fcid ";
                // appends sql statement to corresponding food category id
                $sql .= "WHERE f.fcid = $selected_category AND f.avl = 1";
                $result_filter = mysqli_query($conn, $sql);
                
                //show all food items and details using while loop
                if (mysqli_num_rows($result_filter) > 0) {
                    while ($row = mysqli_fetch_assoc($result_filter)) { ?>
                        <div class="menu-item">
                            <!-- image  -->
                            <img src="assets/<?= $row['img'] ?>" alt="<?= $row['foodname'] ?>">
                            <!-- food name  -->
                            <h3>
                                <?= $row['foodname'] ?>
                            </h3>
                            <!-- food description  -->
                            <p>
                                <?= $row['description'] ?>
                            </p>
                            <!-- food price  -->
                            <p class="price">RM
                                <?= $row['price'] ?>
                            </p>
                            <!-- set minimum value as 1 and default value as 1  -->
                            <input type="number" min="1" value="1" class="quantity">
                            <!-- add to cart button  -->
                            <button class="add-to-cart" data-fid="<?= $row['fid'] ?>">Add to Cart</button>
                        </div>
                    <?php }
                } else {
                    //display message if no food was found
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