<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php'; ?> <!-- For root files -->
<!-- OR use <?php include '../../includes/header.php'; ?> for module files -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monik Group ERP | Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="auth-page">
<div class="card">
    <form id="loginForm">
        <h2 class="brand-title">Monik Group ERP</h2>
        <div id="responseMsg" class="msg"></div>

        <label for="username">Username / Email</label>
        <input id="username" type="text" name="username" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <button type="submit">Access System</button>
    </form>
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
                    $('#responseMsg').addClass('success').text('Success! Redirecting...');
                    window.location.href = response.redirect || 'dashboard.php';
                } else {
                    $('#responseMsg').addClass('error').text(response.message || 'Invalid credentials');
                }
            },
            error: function() {
                $('#responseMsg').addClass('error').text('Server error. Check DB connection.');
            }
        });
    });
});
</script>

</body>
</html>