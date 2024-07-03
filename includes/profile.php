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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $profile_pic = $user['ProfilePic'];

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed = array('jpeg', 'jpg', 'png');
        $file_ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        
        if (in_array($file_ext, $allowed)) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $profile_pic = $target_file;
            }
        }
    }

    $sql = "UPDATE User SET Name='$name', Email='$email', Phone='$phone', ProfilePic='$profile_pic' WHERE Email='$user_email'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $email;
        header("Location: profile.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <title>My Profile</title>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profile-pic');
                output.innerHTML = '<img src="' + reader.result + '" alt="Profile Picture">';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>
    <header>
        <div id="menu-button" onclick="toggleMenu()">☰</div>
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
    <script>
        function toggleMenu() {
            const menu = document.getElementById('hidden-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    <div class="container">
        <h1>My Profile</h1>
        <h2>Manage profile information for account security</h2>
        <div class="separator">
            <hr class="line">
            <hr class="line">
        </div>
        <div class="profile-container">
            <div class="profile-inputs">
                <form method="post" action="profile.php" enctype="multipart/form-data">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="" value="<?php echo htmlspecialchars($user['Name']); ?>">
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="" value="<?php echo htmlspecialchars($user['Email']); ?>">
                    
                    <label for="phone">Phone number</label>
                    <input type="tel" id="phone" name="phone" placeholder="" value="<?php echo htmlspecialchars($user['Phone']); ?>">

                    <div class="photo-section">
                        <label for="profile_pic" class="select-photo-btn">Select Photo</label>
                        <input type="file" id="profile_pic" name="profile_pic" accept=".jpeg,.jpg,.png" hidden onchange="previewImage(event)">
                        <p>Format: JPEG, PNG</p>
                    </div>

                    <button type="submit" class="save-btn">Save</button>
                </form>
            </div>
            <div class="profile-pic-section">
                <div class="profile-pic" id="profile-pic">
                    <?php if ($user['ProfilePic']): ?>
                        <img src="<?php echo htmlspecialchars($user['ProfilePic']); ?>" alt="Profile Picture">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
