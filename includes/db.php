<?php
require_once 'config.php';

global $link;
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($link === false) {
    error_log("ERROR: Could not connect. " . mysqli_connect_error());
    die("ERROR: Could not connect to the database.");
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($link, $sql)) {
    // Log success
    error_log("Database created successfully or already exists.");
} else {
    error_log("ERROR: Could not execute $sql. " . mysqli_error($link));
    die("ERROR: Could not create the database.");
}

// Select the database
mysqli_select_db($link, DB_NAME);

// SQL to create tables
$sql = "
CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS Products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    image_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    shipping_name VARCHAR(255) NOT NULL,
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(255) NOT NULL,
    shipping_state VARCHAR(255) NOT NULL,
    shipping_zip VARCHAR(50) NOT NULL,
    shipping_phone VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS OrderItems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(id),
    FOREIGN KEY (product_id) REFERENCES Products(id)
);";

if (mysqli_multi_query($link, $sql)) {
    do {
        /* store first result set */
        if ($result = mysqli_store_result($link)) {
            while ($row = mysqli_fetch_row($result)) {
                // Fetch result if needed
            }
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($link));
    // Log success
    error_log("Tables created successfully.");
} else {
    error_log("ERROR: Could not execute $sql. " . mysqli_error($link));
    die("ERROR: Could not create tables.");
}

// Check if sample products already exist
$checkProducts = "SELECT COUNT(*) AS count FROM Products";
$result = mysqli_query($link, $checkProducts);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    // Insert sample products if they do not exist
    $sampleProducts = "
   INSERT INTO Products (name, description, price, category, image_url) VALUES
    ('Panasonic NN-SN936B', 'The Panasonic NN-SN936B is a high-capacity microwave with inverter technology for even cooking and a sleek design.', 99.99, 'Microwaves', 'images/1.webp'),
    ('Toshiba EM925A5A-BS', 'Toshiba EM925A5A-BS offers a compact design, easy-to-use features, and reliable performance for everyday use.', 149.99, 'Microwaves', 'images/2.webp'),
    ('Samsung MG14H3020CM', 'Samsung MG14H3020CM features a stylish mirror design, grilling element, and ceramic enamel interior for durability.', 199.99, 'Microwaves', 'images/3.webp'),
    ('LG NeoChef LMC1575ST', 'LG NeoChef LMC1575ST combines smart inverter technology with a sleek design, providing precise cooking and even heating.', 249.99, 'Microwaves', 'images/4.webp'),
    ('Breville BMO850BSS1BUC1', 'Breville BMO850BSS1BUC1, the Quick Touch microwave, features smart settings that automatically adjust cooking time and power for optimal results.', 299.99, 'Microwaves', 'images/5.webp');"
    ;

    if (mysqli_query($link, $sampleProducts)) {
        // Log success
        error_log("Sample products inserted successfully.");
    } else {
        error_log("ERROR: Could not execute $sampleProducts. " . mysqli_error($link));
    }
}
?>