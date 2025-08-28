<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: homepage.html"); 
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT character_img FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($animal);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" type="image/gif" href="../css/assets/bitlog_coin.png">
    <link rel="stylesheet" href="../css/profileDesktop.css"
      media="screen and (min-width: 1441px)">
</head>
<body>
  <div class="sidebar">
    <button class="logo">
      <img src="../css/assets/bitlog_coinanimation.gif" alt="Logo">
    </button>
    <button class="icon">
      <a href="dashboard.php"><img src="../css/assets/home_icon.png" alt="Home"></a>
    </button>
    <button class="icon">
      <a href="wallet.php"><img src="../css/assets/wallet_icon.png" alt="Wallet"></a>
    </button>
    <button class="icon">
      <a href="profile.php"><img src="../css/assets/profile_icon.png" alt="Profile"></a>
    </button>
    <button class="icon logout">
      <a href="logout.php"><img src="../css/assets/logout_icon.png" alt="Logout"><a>
    </button>
  </div>

    <div class="content">
    <button id ="prevButton"><</button>

    <div class="character-wrapper">
        <?php
            echo '<img src="' . $animal . '" id="animalImage">';
        ?>
    </div>

    <button id = "nextButton">></button>

    <div class="setButton">
        <form action="../handlers/editProfile.php" method="POST">
            <input type="hidden" id="selectedAnimal" name="selectedAnimal">
            <button type="submit" id="setAnimal">set</button>
        </form>
    </div>
    </div>

    <script>  
    const animals = [
        "../css/assets/cat.png",
        "../css/assets/chicken.png",
        "../css/assets/dog.png",
        "../css/assets/hamster.png",
        "../css/assets/pig.png",
        "../css/assets/shark.png"
    ];

    let currentIndex = 0;
    const animalImage = document.getElementById("animalImage");
    let setAnimal;

    document.getElementById("prevButton").addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + animals.length) % animals.length;
        animalImage.src = animals[currentIndex];
        document.getElementById("selectedAnimal").value = animals[currentIndex];
    });

    document.getElementById("nextButton").addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % animals.length;
        animalImage.src = animals[currentIndex];
        document.getElementById("selectedAnimal").value = animals[currentIndex];
    });
    </script>

</body>
</html>