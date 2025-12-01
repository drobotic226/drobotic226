<?php
session_start();
include("db.php"); // include DB connection
require_once 'vendor/autoload.php'; // Twilio SDK
use Twilio\Rest\Client;

$flashMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $contact = $_POST['email'] ?? ''; // mobile number

    if (empty($name) || empty($contact)) {
        $flashMessage = "Please enter both Name and Contact.";
    } else {
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+1 minutes"));

        // Store OTP & user info in session
        $_SESSION['otp'] = $otp;
        $_SESSION['user_name'] = $name;
        $_SESSION['auth_user'] = $contact;

        // ✅ Save OTP & expiry in database
        $stmt = $conn->prepare("UPDATE users SET otp=?, otp_expiry=? WHERE email=? OR mobile=?");
        if ($stmt) {
            $stmt->bind_param("ssss", $otp, $expiry, $contact, $contact);
            $stmt->execute();
            $stmt->close();
        }

        // Twilio credentials
        $sid    = "AC676611465035ddd5102ef87ce302555e"; // Your SID
        $token  = "df71f73c29d74e865d123c0b9e359d10";   // Your Token
        $twilio = new Client($sid, $token);

        try {
            $message = $twilio->messages->create(
                "+91" . $contact, // customer number in E.164 format
                [
                    'from' => "+12183062284", // Twilio verified number
                    'body' => "🔐 Use the verification code below to complete your login process:\n\nYour OTP: $otp\n\nThis code will expire in 1 minutes. Do not share it with anyone."
                ]
            );

            // Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit;

        } catch(Exception $e) {
            $flashMessage = "Failed to send OTP: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DROBOTIC</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: 'Roboto', sans-serif; }
body, html { height:100%; background:#f5f5f5; }

.container {
  display: flex;
  min-height: 100vh;
  justify-content: center;
  align-items: center;
}

.login-card {
  display: flex;
  width: 900px;
  height: 500px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  border-radius: 8px;
  overflow: hidden;
  background: #fff;
}

.left-panel {
  flex: 1;
  background: #2874f0;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
  text-align: center;
}

.left-panel h1 {
  font-size: 2rem;
  margin-bottom: 15px;
}

.left-panel p {
  font-size: 1rem;
  margin-bottom: 20px;
}

.left-panel img {
  max-width: 200px;
  margin-top: 20px;
}

.right-panel {
  flex: 1;
  padding: 60px 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.right-panel h2 {
  margin-bottom: 20px;
  color: #333;
  font-size: 1.5rem;
}

.input-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.input-group label {
  font-size: 0.9rem;
  margin-bottom: 5px;
  color: #555;
}

.input-group input {
  padding: 12px 15px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  outline: none;
}

.input-group input:focus {
  border-color: #2874f0;
}

.otp-btn {
  padding: 12px;
  background: #fb641b;
  border: none;
  color: #fff;
  font-size: 1rem;
  font-weight: 500;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
}

.otp-btn:hover {
  background: #e55a16;
}

.right-panel .note {
  font-size: 0.8rem;
  color: #777;
  margin-top: 10px;
}

.right-panel .register {
  margin-top: auto;
  font-size: 0.9rem;
  text-align: center;
}

.right-panel .register a {
  color: #2874f0;
  text-decoration: none;
}

.right-panel .register a:hover {
  text-decoration: underline;
}

.flash-msg {
  background:#ffdd57;
  color:#000;
  padding:12px 20px;
  border-radius:8px;
  font-weight:bold;
  margin-bottom:15px;
  text-align:center;
}

@media(max-width:900px){
  .login-card {
    flex-direction: column;
    height: auto;
  }
  .left-panel, .right-panel {
    flex: none;
    width: 100%;
    padding: 40px 20px;
  }
  .left-panel img { max-width: 150px; }
}
</style>
</head>
<body>

<div class="container">
  <div class="login-card">
    <!-- Left Panel -->
    <div class="left-panel">
      <h1>Login</h1>
      <p>Get access to your Orders, Wishlist and Recommendations</p>
      <img src="images/auth-5b5fdc9c.png" alt="Login Image" class="login-image">
    </div>
    
    <!-- Right Panel -->
    <div class="right-panel">
      <h2>Login to DROBOTIC</h2>

      <?php if($flashMessage): ?>
        <div class="flash-msg"><?= htmlspecialchars($flashMessage) ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="input-group">
          <label for="name">Enter Name</label>
          <input type="text" name="name" id="name" placeholder="Enter your name" required>
        </div>
        <div class="input-group">
          <label for="email">Enter Mobile Number</label>
          <input type="text" name="email" id="email" placeholder="Enter Mobile number" required>
        </div>
        <button type="submit" class="otp-btn">Login & Get OTP</button>
        <p class="note">By continuing, you agree to DROBOTIC's <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
      </form>

      <div class="register">
        New to DROBOTIC? <a href="register.php">Create an account</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
