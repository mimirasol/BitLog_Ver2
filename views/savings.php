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

$stmt = $conn->prepare("SELECT goal_name, target_amount, current_amount FROM savings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$goals = [];
$currentPrices = [];
$targetPrices = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $goals[] = htmlspecialchars($row['goal_name']);
        $currentPrices[] = number_format($row['current_amount'], 2);
        $targetPrices[] = number_format($row['target_amount'], 2);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/gif" href="../css/assets/bitlog_coin.png">
    <link rel="stylesheet" href="../css/savingsDesktop.css"
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
    <div class="profile">
      <button class="icon">
        <a href="profile.php"><?php
              echo '<img src="' . $animal . '" id="profile">';
          ?></a>
      </button>
    </div>
    <button class="icon logout">
      <a href="logout.php"><img src="../css/assets/logout_icon.png" alt="Logout"></a>
    </button>
  </div>

  <div class="content">
    <div class="buttonRow">
        <div class="button log">
            <a href="signup.php">LOG</a>
        </div>
        <div class="button">
          <button id="addButton">ADD</button>
        </div>
        <div class="button">
            <a href="signup.php">DELETE</a>
        </div>
    </div>
    <div class="container">
      <div class="savingsbox">
          Savings
          <p id="goalText">Goal: </p>
          <p id="priceText">P 0.00 / 0.00</p>    
      </div>
      <div class="listbox">
          <?php
              if (!empty($goals) && !empty($currentPrices) && !empty($targetPrices)) {
                for ($i = 0; $i < count($goals); $i++) { //loop to access the array
                    echo "<button class='goalItem'
                      data-goal='{$goals[$i]}' 
                      data-current='{$currentPrices[$i]}' 
                      data-target='{$targetPrices[$i]}'>
                      <span class='itemName'>{$goals[$i]}</span>
                      <span class='itemPrice'>{$targetPrices[$i]}</span>
                    </button>";
                }
              } else {
                  echo "No savings";
              }
          ?>
      </div>
   </div>
  </div>

  <script> //showing list
      let activeDiv = null; // global variable

      document.getElementById("addButton").addEventListener("click", ()=> {
          activeDiv = document.getElementById("addItem");
          activeDiv.style.display = 'flex';
      });

      document.querySelectorAll(".closeButton").forEach(button => {
          button.addEventListener("click", () => {
              if (activeDiv) {
              activeDiv.style.display = 'none';
              activeDiv = null;
              }
          });
      });

      const urlParams = new URLSearchParams(window.location.search);
      const errorMessage = urlParams.get('error');
      const errorType = urlParams.get('type');

      if (errorMessage && errorType) {
        if (errorType === "add") {
            let errorElementAdd = document.getElementById("error-message-add");
            errorElementAdd.textContent = errorMessage;
            errorElementAdd.style.display = "block";
            document.getElementById("addItem").style.display = 'flex';
            activeDiv = document.getElementById("addItem");
        }
      }

  </script>

  <script> //selecting from list
  document.querySelectorAll(".goalItem").forEach(button => {
    button.addEventListener("click", () => {
      const goal = button.dataset.goal;
      const current = button.dataset.current;
      const target = button.dataset.target;

      // Update savings box
      document.getElementById("goalText").textContent = "Goal: " + goal;
      document.getElementById("priceText").textContent = "P " + current + " / " + target;
    });
  });
</script>
</body>
</html>