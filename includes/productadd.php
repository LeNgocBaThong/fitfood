<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitfood";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product categories
$sql = "SELECT * FROM Product_category";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $info = $_POST['info'];
    $image = '';

    // Handle image upload
    if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] == 0) {
        $allowed = array('jpeg', 'jpg', 'png');
        $file_ext = pathinfo($_FILES['file-input']['name'], PATHINFO_EXTENSION);

        if (in_array($file_ext, $allowed)) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES['file-input']['name']);
            if (move_uploaded_file($_FILES['file-input']['tmp_name'], $target_file)) {
                $image = $target_file;
            }
        }
    }

    // Insert product into database
    $sql = "INSERT INTO Product (Name, Price, Description, Image, Product_category_id) VALUES ('$name', '$price', '$info', '$image', '$category')";
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/productadd.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('image-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function triggerFileInput() {
            document.getElementById('file-input').click();
        }

        // Function to toggle the menu visibility
        function toggleMenu() {
            const menu = document.getElementById('hidden-menu');
            menu.classList.toggle('hidden');
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
            <form method="POST" enctype="multipart/form-data">
                <div class="top-row">
                    <div class="image-upload">
                        <img src="https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg" alt="" id="image-preview">
                        <input type="file" id="file-input" name="file-input" accept="image/*" onchange="previewImage(event)">
                        <button type="button" class="upload-btn" onclick="triggerFileInput()">Upload</button>
                    </div>
                    <div class="input-fields">
                        <div class="input-group">
                            <h3>Name</h3>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="input-group">
                            <h3>Price</h3>
                            <input type="number" step="0.01" id="price" name="price" required>
                        </div>
                        <div class="input-group">
                            <h3>Category</h3>
                            <select id="category" name="category" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <h3>Info</h3>
                    <textarea id="info" name="info"></textarea>
                </div>
                <button type="submit" class="add-btn">Add</button>
            </form>
        </div>
    </div>
</body>
</html>