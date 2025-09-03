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
    <link rel="stylesheet" href="../css/dashboardDesktop.css"
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
  </div>
</body>
</html>