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

// Handle form submission for adding voucher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['voucherName'])) {
    $voucherName = $_POST['voucherName'];
    $quantity = $_POST['quantity'];

    // Check if voucher with the same name exists
    $sql = "SELECT * FROM Voucher WHERE name = '$voucherName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update quantity if voucher exists
        $sql = "UPDATE Voucher SET so_luong = so_luong + $quantity WHERE name = '$voucherName'";
    } else {
        // Insert new voucher if it doesn't exist
        $sql = "INSERT INTO Voucher (name, so_luong) VALUES ('$voucherName', '$quantity')";
    }

    if ($conn->query($sql) === TRUE) {
        //echo "Voucher added/updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle delete voucher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteVoucher'])) {
    $voucherId = $_POST['deleteVoucher'];
    $sql = "DELETE FROM Voucher WHERE id = $voucherId";
    $conn->query($sql);
}

// Handle toggle status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggleStatus'])) {
    $voucherId = $_POST['toggleStatus'];
    $status = $_POST['status'];
    $sql = "UPDATE Voucher SET status = $status WHERE id = $voucherId";
    $conn->query($sql);
}

// Fetch all vouchers
$sql = "SELECT * FROM Voucher";
$result = $conn->query($sql);
$vouchers = [];
while ($row = $result->fetch_assoc()) {
    $vouchers[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Page</title>
    <link rel="stylesheet" href="../assets/css/voucher.css">
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
    <hr class="line-2">
    <script>
        function toggleMenu() {
            const menu = document.getElementById('hidden-menu');
            menu.classList.toggle('hidden');
        }

        function openForm() {
            document.getElementById("voucherForm").classList.add('open');
            document.querySelector('.overlay').style.display = 'block';
        }

        function closeForm() {
            document.getElementById("voucherForm").classList.remove('open');
            document.querySelector('.overlay').style.display = 'none';
        }
    </script>
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
            <div class="top-bar">
                <button class="add-voucher" onclick="openForm()">Add Voucher</button>
            </div>
            <div class="overlay">
                <div class="form-popup" id="voucherForm">
                    <form class="form-container" method="POST">
                        <h2>Add Voucher</h2>
                        <label for="voucherName"><b>Name Voucher</b></label>
                        <input type="text" id="voucherName" name="voucherName" required>
                        <label for="quantity"><b>Quantity</b></label>
                        <input type="number" id="quantity" name="quantity" required>
                        <button type="submit" class="btn">Add</button>
                        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                    </form>
                </div>
            </div>
            <?php foreach ($vouchers as $voucher): ?>
                <div class="voucher">
                    <span><?php echo $voucher['name']; ?></span>
                    <span class="quantity">x<?php echo $voucher['so_luong']; ?></span>
                    <div class="actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="toggleStatus" value="<?php echo $voucher['id']; ?>">
                            <input type="hidden" name="status" value="<?php echo $voucher['status'] ? 0 : 1; ?>">
                            <label class="switch">
                                <input type="checkbox" <?php echo $voucher['status'] ? 'checked' : ''; ?> onclick="this.form.submit()">
                                <span class="slider round"></span>
                            </label>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="deleteVoucher" value="<?php echo $voucher['id']; ?>">
                            <button type="submit" class="delete">🗑️</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
