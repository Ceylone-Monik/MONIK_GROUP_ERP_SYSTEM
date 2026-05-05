<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Monik Group</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="auth-body">

<div class="split-container">
    <!-- DETAILS LEFT SIDE -->
    <div class="left-branding">
        <div class="branding-content">
            <img src="assets/pic/MonikLogoOnly.png" alt="Monik Logo" class="login-logo">
            <h1>MONIK <span style="color:#e74c3c;">INTERNATIONAL</span></h1>
            <p class="quote">"At Monik International, we furnish you with a range of financial services and quality assistance that drive your expectations to success."</p>
            
            <div class="stats-row">
                <span><strong>19+</strong> Branches</span>
                <span><strong>20000+</strong> Happy Clients</span>
            </div>
        </div>
    </div>

    <!-- LOGIN CONTAINER RIGHT SIDE -->
    <div class="right-login">
        <div class="login-box">
            <h2>System Login</h2>
            <p>Please enter your credentials to continue.</p>
            
            <div id="responseMsg" class="msg"></div>

            <form id="loginForm">
                <div class="input-group">
                    <label>Username / Email</label>
                    <input type="text" name="username" placeholder="pawanimsara12@gmail.com" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••" required>
                </div>

                <button type="submit" class="btn-access">Access System →</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        $('#responseMsg').removeClass('error success').text('Checking credentials...');
        $.ajax({
            url: 'auth/login_process.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success' || response.ok === true) {
                    window.location.href = 'dashboard.php';
                } else {
                    $('#responseMsg').addClass('error').text(response.message || 'Invalid credentials');
                }
            }
        });
    });
});
</script>
</body>
</html>