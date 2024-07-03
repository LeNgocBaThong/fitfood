<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitfood";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details for update
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM product WHERE ID=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Product not found'); window.location.href='productlist.php';</script>";
        exit;
    }
}

// Update product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $discount = $_POST['discount'] === 'None' ? null : $_POST['discount'];
    $status = $_POST['status'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $validExtensions = array("jpg", "jpeg", "png");

        if (in_array($imageFileType, $validExtensions)) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($image);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, and PNG files are allowed.'); window.location.href='productupdate.php?id=$id';</script>";
            exit;
        }
    } else {
        $image = $_POST['existing_image'];
    }

    $sql = "UPDATE product SET Name='$name', Price='$price', Description='$description', discount='$discount', Image='$image', Status='$status' WHERE ID=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product updated successfully'); window.location.href='productlist.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Update</title>
    <link rel="stylesheet" href="../assets/css/productupdate.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <script>
        // Function to toggle the menu visibility
        function toggleMenu() {
            const menu = document.getElementById('hidden-menu');
            menu.classList.toggle('hidden');
        }

        // Function to preview image before upload
        function previewImage(event) {
            const file = event.target.files[0];
            const validExtensions = ['image/jpeg', 'image/png'];

            if (!validExtensions.includes(file.type)) {
                alert('Invalid file type. Only JPG, JPEG, and PNG files are allowed.');
                event.target.value = ''; // Clear the input
                return;
            }

            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('productImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
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
            <div class="product-form">
                <div class="image-section">
                    <img src="<?php echo $product['Image']; ?>" alt="Product Image" id="productImage"><br>
                    <input type="file" id="uploadImage" name="image" onchange="previewImage(event)" style="display: none;">
                    <button class="upload-btn" onclick="document.getElementById('uploadImage').click(); return false;">Upload</button>
                </div>
                <div class="form-section">
                    <form method="post" action="productupdate.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $product['ID']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo $product['Image']; ?>">
                        <label for="productName">Name</label>
                        <input type="text" id="productName" name="name" value="<?php echo $product['Name']; ?>">

                        <label for="productPrice">Price</label>
                        <input type="text" id="productPrice" name="price" value="<?php echo $product['Price']; ?>">

                        <label for="productDescription">Description</label>
                        <textarea id="productDescription" name="description"><?php echo $product['Description']; ?></textarea>

                        <label for="productDiscount">Discount</label>
                        <select id="productDiscount" name="discount">
                            <option value="None" <?= is_null($product['discount']) ? 'selected' : '' ?>>None</option>
                            <?php for ($i = 10; $i <= 70; $i += 10) { ?>
                                <option value="<?= $i ?>" <?= $product['discount'] == $i ? 'selected' : '' ?>><?= $i ?>%</option>
                            <?php } ?>
                        </select>

                        <label for="productStatus">Status</label>
                        <select id="productStatus" name="status">
                            <option value="1" <?= $product['Status'] == 1 ? 'selected' : '' ?>>On</option>
                            <option value="2" <?= $product['Status'] == 2 ? 'selected' : '' ?>>Off</option>
                        </select>

                        <div class="buttons">
                            <button type="submit" class="submit-btn">Update Product</button>
                            <a href="productlist.php" class="back-btn">Trở lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>