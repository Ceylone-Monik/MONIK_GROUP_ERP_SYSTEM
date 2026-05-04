<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monik Group ERP | Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Professional Login Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a; /* Dark theme background */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        #responseMsg {
            margin-bottom: 15px;
            font-size: 14px;
            min-height: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <form id="loginForm">
        <h2>Monik Group ERP</h2>
        <div id="responseMsg"></div>
        <input type="text" name="username" placeholder="Username / Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Access System</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        // Reset message
        $('#responseMsg').css('color', 'gray').text('Checking credentials...');

        $.ajax({
            url: 'auth/login_process.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.status === 'success') {
                    $('#responseMsg').css('color', 'green').text('Success! Redirecting...');
                    window.location.href = 'dashboard.php';
                } else {
                    $('#responseMsg').css('color', 'red').text(response.message);
                }
            },
            error: function() {
                $('#responseMsg').css('color', 'red').text('Server error. Check DB connection.');
            }
        });
    });
});
</script>

</body>
</html>