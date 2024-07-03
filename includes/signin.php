<?php
session_start();
$login_successful = false;
$login_failed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'fitfood');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM User WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['username'] = $row['Email'];
            $login_successful = true;
        } else {
            $login_failed = true;
        }
    } else {
        $login_failed = true;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitFood - Sign in</title>
    <link rel="stylesheet" href="../assets/css/signin.css">
    <script>
        function showFailureMessage() {
            alert("Login unsuccessful. Please check your email and password.");
        }
    </script>
</head>
<body>
    <?php if ($login_successful): ?>
        <script>
            window.location.href = "status.php";
        </script>
    <?php elseif ($login_failed): ?>
        <script>
            showFailureMessage();
        </script>
    <?php endif; ?>
    <div class="container">
        <h1 class="logo">FITFOOD</h1>
        <hr class="line">
        <p class="sub-header">
            <img src="../assets/images/star.png" alt="fb" class="star-logo">
            Sign in to access your credits and discounts</p>
        <h2>Sign In</h2>
        <button class="social-button google-button">
            <img src="../assets/images/google.png" alt="GG" class="social-logo">
                Continue with Google
        </button>
        <button class="social-button facebook-button">
            <img src="../assets/images/facebook.png" alt="fb" class="social-logo">
                Continue with Facebook
        </button>
        <div class="separator">
            <hr class="line">
            <span>Sign In With Email</span>
            <hr class="line">
        </div>
        <form id="signin-form" method="post" action="">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" class="signin-button">Sign In</button>
        </form>
    </div>
    <script src="../scripts/main.js"></script>
</body>
</html>
