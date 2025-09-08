<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.html"); 
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
    <link rel="stylesheet" href="../css/profileMobile.css"
      media="screen and (min-width: 320px)">
    <link rel="stylesheet" href="../css/profileDesktop.css"
      media="screen and (min-width: 1000px)">
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
    <div class="profile">
      <button class="icon">
        <a href="profile.php"><?php
              echo '<img src="' . $animal . '" id="profile">';
          ?></a>
      </button>
    </div>
    <button class="icon logout">
      <a href="logout.php"><img src="../css/assets/logout_icon.png" alt="Logout"><a>
    </button>
  </div>

  <div class="wrapper">
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

    <div class="labelBox">
          <p>User Information</p>
      </div>

      <div class="userInfo">
          <form action="../handlers/editUsername.php" method="POST" class="editForm">
              <label for="editName">Name:</label>
              <?php
                  echo '<input type="text" id="username" name="editUsername" placeholder="'.$username . '">';
              ?>
              <button type="submit" id="setUsername">edit</button>
              <p id="errorMessage" style="display: none;"></p>
              <p id="confirmationMessage" style="display: none;"></p>
          </form>
      </div>
    </div>
  </div>

    <script>
    const wallet = document.querySelector('a[href="wallet.php"]');
    
    if (wallet) {
      wallet.addEventListener ("click", function(e) {
      sessionStorage.setItem("walletPage", "true");
      });
    }

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

    const error = new URLSearchParams(window.location.search);
    const URLError = error.get("error");
    if (URLError) {
        document.getElementById("errorMessage").textContent = URLError;
        document.getElementById("errorMessage").style.display = "block";
    }

    const confirm = new URLSearchParams(window.location.search);
    const URLConfirm = confirm.get("username");
    if (URLConfirm) {
        document.getElementById("confirmationMessage").textContent = URLConfirm;
        document.getElementById("confirmationMessage").style.display = "block";
    }
    </script>

</body>
</html>