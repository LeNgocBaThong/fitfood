<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitfood";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['username'];
$sql = "SELECT * FROM User WHERE Email='$user_email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$user_id = $user['ID'];

$orders = [];
$sql = "SELECT * FROM `Order` WHERE User_id='$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/status.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <script src="../scripts/main.js"></script>
    <title>Delivery Status</title>
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
    <script>
        function toggleMenu() {
            const menu = document.getElementById('hidden-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    <div class="status-order">
        <div class="button-nav">
            <button class="nav-button" onclick="filterOrders(1)">Delivered</button>
            <button class="nav-button" onclick="filterOrders(0)">Are Delivering</button>
            <button class="nav-button" onclick="filterOrders(-1)">Cancelled</button>
        </div>
        <div class="content-order" id="order-content">
            <!-- Orders will be displayed here -->
        </div>
    </div>
    <script>
        const orders = <?php echo json_encode($orders); ?>;

        function filterOrders(status) {
            const content = document.getElementById('order-content');
            content.innerHTML = ''; // Clear previous content

            const filteredOrders = orders.filter(order => {
                if (status === -1) {
                    return order.status === null;
                }
                return order.status == status;
            });

            filteredOrders.forEach(order => {
                const orderDiv = document.createElement('a');
                orderDiv.classList.add('order-item');
                orderDiv.href = "#";
                orderDiv.innerHTML = `
                    <p>Order ID: ${order.id}</p>
                    <p>Order Date: ${order.order_date}</p>
                    <p>Total Amount: ${order.total_amount} VND</p>
                `;
                content.appendChild(orderDiv);
            });

            if (filteredOrders.length === 0) {
                content.innerHTML = '<p>No orders found for this status.</p>';
            }
        }

        // Initially show delivered orders
        filterOrders(1);
    </script>
</body>
</html>
