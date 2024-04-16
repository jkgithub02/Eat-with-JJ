
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- admin table
CREATE TABLE admin (
    aid INT NOT NULL PRIMARY KEY, 
    username VARCHAR(50) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL
);

-- password is admin123, password hashing used here
INSERT INTO admin(aid, username, password) VALUES
(1, "admin123", "$2y$10$alp7ZWTEoEg.Qzk9e6MTCuai1VsYkhhnCGAl46rBPqYL.a52y3vjG");


-- User Table
CREATE TABLE user (
    uid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(222) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    address VARCHAR(255)
);

CREATE TABLE foodcat (
    fcid INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    catname VARCHAR(50) NOT NULL
);

INSERT INTO foodcat (fcid, catname) VALUES
(1, "Burgers"),
(2, "Pasta"),
(3, "Pizza"),
(4, "Beverages");


CREATE TABLE food(
    fid INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    fcid INT NOT NULL,
    foodname VARCHAR(100) NOT NULL,
    description TEXT, 
    price DECIMAL(10,2) NOT NULL,
    img VARCHAR(222) NOT NULL,
    --avl '1' means available, avl '0' means unavailable
    avl INT NOT NULL DEFAULT 1,
    FOREIGN KEY (fcid) REFERENCES foodcat(fcid)
);

INSERT INTO food(fid, fcid, foodname, description, price, img) VALUES
(1, 1, "Grilled Chicken Burger", "Lorem Ipsum", "10.00", "gc_burger.jpeg"),
(2, 1, "Fried Chicken Burger", "Lorem Ipsum", "10.00", "fc_burger.jpeg"),
(3, 1, "Fish Burger", "Lorem Ipsum", "10.00", "fish_burger.jpeg"),
(4, 1, "Steak Burger", "Lorem Ipsum", "10.00", "steak_burger.jpeg"),
(5, 1, "Vegan Burger", "Lorem Ipsum", "10.00", "veg_burger.jpeg"),
(6, 2, "Aglio e Olio Pasta", "Lorem Ipsum", "11.00", "aglioolio.jpeg"),
(7, 2, "Bolognese Pasta", "Lorem Ipsum", "11.00", "bolognese.jpeg"),
(8, 2, "Carbonara Pasta", "Lorem Ipsum", "11.00", "carbonara.jpeg"),
(9, 2, "Lobster Pasta", "Lorem Ipsum", "11.00", "lobster.jpeg"),
(10, 2, "Pesto Pasta", "Lorem Ipsum", "11.00", "pesto.jpeg"),
(11, 3, "Cheese Pizza", "Lorem Ipsum", "12.00", "cheese.jpeg"),
(12, 3, "Hawaiian Pizza", "Lorem Ipsum", "12.00", "hawaiian.jpeg"),
(13, 3, "Margherita Pizza", "Lorem Ipsum", "12.00", "margherita.jpeg"),
(14, 3, "Pepperoni Pizza", "Lorem Ipsum", "12.00", "pepperoni.jpeg"),
(15, 3, "Seafood Pizza", "Lorem Ipsum", "12.00", "seafood.jpeg"),
(16, 4, "Iced Lemon Tea", "Lorem Ipsum", "5.00", "ilt.jpeg"),
(17, 4, "Citrus Juice", "Lorem Ipsum", "5.00", "juice.jpeg"),
(18, 4, "Iced Latte", "Lorem Ipsum", "5.00", "latte.jpeg"),
(19, 4, "Iced Mocha", "Lorem Ipsum", "5.00", "mocha.jpeg"),
(20, 4, "Hibiscus Tea", "Lorem Ipsum", "5.00", "hibtea.jpeg");




CREATE TABLE orders ( 
  oid INT AUTO_INCREMENT NOT NULL PRIMARY KEY, 
  uid INT NOT NULL,
  fid INT NOT NULL,
  quantity INT NOT NULL,
  --status 0 means preparing, 1 means completed
  sid INT NOT NULL DEFAULT 0,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  FOREIGN KEY (uid) REFERENCES user(uid),
  FOREIGN KEY (fid) REFERENCES food(fid)
);


COMMIT; 

