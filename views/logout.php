<?php
session_start();
session_unset();
session_destroy();


$redirectUrl = "../index.html";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging out...</title>
    <meta http-equiv="refresh" content="3;url=<?php echo $redirectUrl; ?>">

    <style>
        @font-face {
            font-family: subheaderfont;
            src: url('../css/fonts/prstartk.ttf');
        }

        body {
            background-color: #ffbf00;
            font-family: subheaderfont, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }

        .message {
            color:  #fffcf3;
            font-size: 10vw;
            text-align: center;
            animation:
                fadeSlideIn 1s ease-out forwards,
                pixelBlink 0.8s ease-in-out infinite;
        }

        @keyframes fadeSlideIn {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pixelBlink {
            0%, 100% {
                filter: brightness(1);
            }
            50% {
                filter: brightness(1.5);
            }
        }
    </style>
</head>
<body>
    <div class="message">Bye!</div>
</body>
</html>