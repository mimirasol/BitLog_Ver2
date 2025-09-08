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

$stmt = $conn->prepare("SELECT amount FROM allowances WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($allowance);
$stmt->fetch();
$stmt->close();
$allowance = $allowance ?? number_format(0, 2);

$stmt = $conn->prepare("SELECT SUM(e.amount) FROM expenses e JOIN items i ON e.item_id = i.item_id WHERE i.user_id = ? ");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_expenses);
$stmt->fetch();
$stmt->close();
$total_expenses = $total_expenses ?? number_format(0, 2);

$budget = $allowance - $total_expenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/gif" href="../css/assets/bitlog_coin.png">
    <link rel="stylesheet" href="../css/dashboardMobile.css"
      media="screen and (min-width: 320px)">
    <link rel="stylesheet" href="../css/dashboardDesktop.css"
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
      <a href="logout.php"><img src="../css/assets/logout_icon.png" alt="Logout"></a>
    </button>
  </div>

  <div class="wrapper">
    <div class="content">
      <div class="character-wrapper">
        <?php
              echo '<img src="' . $animal . '" id="animalImage">';
          ?>
        <div class="overlay-text">Hello, <?php echo $username; ?>!</div>
      </div>

      <div class="container allowance">
        Allowance
        <span class="value"><?php echo $allowance; ?></span>
      </div>
      <div class="container budget">
        Budget
        <span class="value"><?php echo number_format($budget, 2, '.', ''); ?></span>
      </div>
      <div class="container expenses">
        Expenses
        <span class="value"><?php echo $total_expenses; ?></span>
      </div>

      <button id="addButton">add</button>
    </div>
  </div>

  <div id="addItem" style="display: none;" class="overlay">
      <button class="closeButton" id="closeButton">X</button>
      
      <div class="inputBox">
          <form action="../handlers/addItemDashboard.php" method="POST" class="addForm">
              <label for="item">add item</label>
              <input type="text" id="addItem" name="addItem">

              <label for="item" id="amountLabel">amount</label>
              <input type="text" id="addAmount" name="addAmount">

              <button type="submit" id="addItemButton">add</button>
              <p id="error-message-add" style="display: none;"></p>
          </form>
      </div>
  </div>

  <script>
    const wallet = document.querySelector('a[href="wallet.php"]');

    if (wallet) {
      wallet.addEventListener ("click", function(e){
      sessionStorage.setItem ("walletPage", "true");
      });
    }

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
</body>
</html>