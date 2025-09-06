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
$stmt->close();

$stmt = $conn->prepare("SELECT goal_name AS itemList, goal_id AS item_id FROM savings WHERE user_id = ?");
$stmt->bind_param ("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$itemList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itemList[] = [
            'id'=> htmlspecialchars($row['item_id']),
            'item_name' => htmlspecialchars($row['itemList'])
        ];
    }
} //result of the query is stored in their respective arrays
$stmt->close();
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
          <button id="logButton">LOG</button>
        </div>
        <div class="button">
          <button id="addButton">ADD</button>
        </div>
        <div class="button">
          <button id="deleteButton">DELETE</button>
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
                      data-id='{$itemList[$i]['id']}'
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

  <div id="setAllowance" style="display: none;" class="overlay">
    <button class="closeButton" id="closeButton">X</button>
    
    <div class="inputBox">
        <form action="../handlers/logSaving.php" method="POST" class="allowanceForm">
            <label for="allowance">current amount</label>
            <input type="text" id="amount" name="amount">
            <input type="hidden" id="goal_id" name="goal_id">
            <button type="submit" id="setAllowanceButton">log</button>
        </form>
    </div>
    <p id="error-message-allowance" style="display: none;"></p>
  </div>
  
  <div id="addItem" style="display: none;" class="overlay">
    <button class="closeButton" id="closeButton">X</button>
    
    <div class="inputBox">
        <form action="../handlers/addSaving.php" method="POST" class="addForm">
            <label for="item">add item</label>
            <input type="text" id="addItem" name="addItem">

            <label for="item" id="amountLabel">target amount</label>
            <input type="text" id="addAmount" name="addAmount">

            <button type="submit" id="addItemButton">add</button>
        </form>
    </div>
    <p id="error-message-add" style="display: none; margin-top: 15%;" id="addMessage"></p>
  </div>

  <div id="deleteItem" style="display: none;" class="overlay">
    <button class="closeButton" id="closeButton">X</button>
    
    <div class="inputBox">
        <form action="../handlers/deleteSaving.php" method="POST" class="deleteForm">
            <label for="item">delete goal</label>
            <select name="itemsDropdown" id="itemsDropdown">
                <?php
                    foreach ($itemList as $option) {
                        echo "<option value=\"{$option['id']}\">{$option['item_name']}</option>";
                    }
                ?>
            </select>
            
            <button type="submit" id="deleteItemButton">delete</button>
        </form>
    </div>
    <p id="error-message-delete" style="display: none; margin-top: 10%;" id="deleteMessage"></p>
  </div>

  <script> //showing list
      let activeDiv = null; // global variable

      document.getElementById("logButton").addEventListener("click", ()=> {
          activeDiv = document.getElementById("setAllowance");
          activeDiv.style.display = 'flex';
      });
      
      document.getElementById("addButton").addEventListener("click", ()=> {
          activeDiv = document.getElementById("addItem");
          activeDiv.style.display = 'flex';
      });

      document.getElementById("deleteButton").addEventListener("click", ()=> {
          activeDiv = document.getElementById("deleteItem");
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
        } else if (errorType === "delete") {
            let errorElementDelete = document.getElementById("error-message-delete");
            errorElementDelete.textContent = errorMessage;
            errorElementDelete.style.display = "block";
            document.getElementById("deleteItem").style.display = 'flex';
            activeDiv = document.getElementById("deleteItem");
        }
      }

  </script>

  <script> //selecting from list
  document.querySelectorAll(".goalItem").forEach(button => {
    button.addEventListener("click", () => {
      const goal = button.dataset.goal;
      const current = button.dataset.current;
      const target = button.dataset.target;
      const goalId = button.dataset.id;

      // Update savings box
      document.getElementById("goalText").textContent = "Goal: " + goal;
      document.getElementById("priceText").textContent = "P " + current + " / " + target;

      document.getElementById("goal_id").value = goalId;
    });
  });
</script>
</body>
</html>