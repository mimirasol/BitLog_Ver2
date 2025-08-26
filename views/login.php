<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="icon" type="image/gif" href="../css/assets/bitlog_coinanimation.gif">
    <link rel="stylesheet" href="../css/loginDesktop.css"
      media="screen and (min-width: 1441px)">
</head>
<body>
    <div class="signupPage">
        <div class="title">
            <img src="../css/assets/bitlog.png" id="bitlog">
            <p>Embark on a pixel-powered money quest where you manage your 
                <br>gold, defeat overspending, and unlock savings milestones!</p>
        </div>

    <div class="buttons">
        <div class="signupButton">
            <a href="signup.php">SIGN UP</a>
        </div>

    </div>

    <div class="labelBox">
        <p>LOG IN</p>
    </div>

    <div class="signupBox">
        <form action="../handlers/user.php" method="POST" class="signupForm">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <p id="error-message" style="display: none;"></p>

            <button type="submit" class="submitButton">enter</button>
        </form>
    </div>

    <script>
        const urlError = new URLSearchParams(window.location.search);
        const errorMessage = urlError.get('error');
        if (errorMessage) {
            document.getElementById("error-message").textContent = errorMessage;
            document.getElementById("error-message").style.display = "block";
        }
    </script>
    
</body>
</html>