<?php
// Database connection
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitfood";
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch products
$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../assets/css/productlist.css">
    <link rel="stylesheet" href="../assets/css/header.css">
</head>
<body>
<header>
        <div id="menu-button" onclick="toggleMenu()">☰</div>

    <!-- Hidden Menu -->
    <div id="hidden-menu" class="hidden">
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
            
        </div>
        <div class="main-logo">
        <div class="logo">
            <img src="../assets/images/Logox100.png" alt="Logo">
        </div>
        <div class="name-logo">
            <p><a href="">FITFOOD</a></p>
        </div>
        </div>
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search Food and Drinks...">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="cta">
            <a href="#signup" class="button">🛒 0</a>
        </div>
    </header>
    <script>
        // Function to toggle the menu visibility
        function toggleMenu() {
            const menu = document.getElementById('hidden-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    <hr class="line-2">
    <div class="container">
    <div class="sidebar">
            <ul>
                <h3>Actions</h3>
                <hr class="line">
                <li><a href="../includes/productadd.php">Add Product</a></li>
                <hr class="line">
                <li><a href="../includes/productlist.php">List Product</a></li>
                <hr class="line">
                <li><a href="../includes/voucher.php">Voucher</a></li>
                <hr class="line">
            </ul>
        </div>
        <div class="main-content">
            <div class="product-list">
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <div class="product">
                    <img src="<?= $row['Image']; ?>" alt="<?= $row['Name']; ?>">
                    <div class="product-details">
                        <h3><?= $row['Name']; ?></h3>
                        <p>Price: <?= number_format($row['Price'], 0, ',', '.'); ?>đ</p>
                        <button class="edit-btn" onclick="navigateTo('productupdate.php ?id=<?= $row['ID']; ?>')">Edit</button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
