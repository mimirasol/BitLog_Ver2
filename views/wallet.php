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

$stmt = $conn->prepare("SELECT i.item_name AS item, e.amount AS amount FROM expenses e JOIN items i ON e.item_id = i.item_id WHERE i.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$prices = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = htmlspecialchars($row['item']);
        $prices[] = number_format($row['amount'], 2);
    }
} //result of the query is stored in their respective arrays
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/gif" href="../css/assets/bitlog_coin.png">
    <link rel="stylesheet" href="../css/walletDesktop.css"
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
        <button id="setButton">SET</button>

        <div class="container allowance">Allowance <?php echo $allowance; ?></div>
        <div class="container budget">Budget <?php echo number_format($budget, 2, '.', ''); ?></div>
        <div class="container expenses">Expenses <?php echo $total_expenses; ?></div>

        <button id="addButton">ADD</button>
        <button id="deleteButton">DELETE</button>
        <div class="expensesBox">
            <ul class = "items">
                <?php
                    if (!empty($items) && !empty($prices)) {
                        for ($i = 0; $i < count($items); $i++) { //loop to access the array
                            echo "<li><span class='itemName'>{$items[$i]}</span>
                                    <span class='itemPrice'>{$prices[$i]}</span></li>";
                        }
                    } else {
                        echo "<li>No expenses</li>";
                    }
                ?>
            </ul>
        </div>

        <div class="savingsButton">
            <a href="savings.php">savings</a>
        </div>
    </div>

    <div id="setAllowance" style="display: none;" class="overlay">
        <button class="closeButton">X</button>
        
        <div class="inputBox">

            <label for="allowance">allowance</label>
            <input type="text">
            
        </div>
    </div>

    <div id="addItem" style="display: none;" class="overlay">
        <button class="closeButton">X</button>
        
        <div class="inputBox">

            <label for="addItem">item</label>
            <input type="text">
            
        </div>
    </div>

    <script>
        let activeDiv = null; // global variable

        document.getElementById("setButton").addEventListener("click", ()=> {
            activeDiv = document.getElementById("setAllowance");
            activeDiv.style.display = 'flex';
        });

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
    </script>
</body>
</html>