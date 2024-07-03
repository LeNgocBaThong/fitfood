<?php
$registration_successful = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', '', 'fitfood');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO User (Email, Phone, Password, Role)
            VALUES ('$email', '$mobile', '$password', 0)";

    if ($conn->query($sql) === TRUE) {
        $registration_successful = true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitFood - Sign Up</title>
    <link rel="stylesheet" href="../assets/css/signup.css">
    <script>
        function showSuccessMessage() {
            alert("Đăng ký thành công, hãy sử dụng tài khoản đó để đăng nhập");
        }
    </script>
</head>
<body>
    <?php if ($registration_successful): ?>
        <script>
            showSuccessMessage();
        </script>
    <?php endif; ?>
    <div class="container">
        <h1 class="logo">FITFOOD</h1>
        <hr class="line">
        <p class="sub-header">
            <img src="../assets/images/star.png" alt="fb" class="star-logo">
            Sign up and get your welcome deals</p>
        <h2>Sign Up</h2>
        <p class="sign-in">Already have an account? <a href="signin.php">Sign in</a></p>
        <form id="signup-form" method="post" action="">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="tel" id="mobile" name="mobile" placeholder="Mobile Number" required>
            <input type="password" id="password" name="password" placeholder="Password (At least 10 characters)" required minlength="10">
            <p class="terms">
                By tapping "Sign Up" or "Continue With Google or Facebook" you agree to FitFood's 
                <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.
            </p>
            <button type="submit" class="signup-button">Sign Up</button>
            <div class="separator">
                <hr class="line">
                <span>OR</span>
                <hr class="line">
            </div>
            <button type="button" class="google-button">
                <img src="../assets/images/google.png" alt="GG" class="social-logo">
                Continue with Google
            </button>
            
            <button type="button" class="facebook-button">
                <img src="../assets/images/facebook.png" alt="fb" class="social-logo">
                Continue with Facebook
            </button>
        </form>
    </div>
    <script src="../scripts/main.js"></script>
</body>
</html>
