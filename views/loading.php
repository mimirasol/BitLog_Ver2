<?php
session_start();

$redirectUrl = "";

if (isset($_SESSION['new_user']) && $_SESSION['new_user'] === true) {
    $redirectUrl = "dashboard.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging in...</title>
    <meta http-equiv="refresh" content="3;url=<?php echo $redirectUrl; ?>">

    <style>
        @font-face {
            font-family: subheaderfont;
            src: url('../css/fonts/prstartk.ttf');
        }

        body {
            background-color: #fffcf3;
            font-family: subheaderfont, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }

        @media (min-width: 320px) {
            .title img {
                width: 70vw;
                margin-top: 10vh;
                animation: fadeSlideIn 1s ease-out forwards,
                            pixelBlink 1s ease-in-out infinite;
            }

            .gifLogo img {
                width: 35vw;
                animation: fadeSlideIn 1s ease-out forwards;
                margin-top: -23vh;
                margin-left: -52vw;
                position: absolute;
            }
        }

        @media (min-width: 1000px) {
            .title img {
                width: 40vw;
                margin-top: -3vh;
                margin-left: 25vw;
            }

            .gifLogo img {
                width: 25vw;
                margin-top: -27vh;
                margin-left: -135vh;
            }
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
    <div class="title">
        <img src="../css/assets/bitlog.png" id="bitlog">
    </div>
    
    <div class="gifLogo">
        <img src="../css/assets/bitlog_coinanimation.gif">
    </div>
</body>
</html>
