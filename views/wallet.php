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
$stmt->close();

$stmt = $conn->prepare("SELECT item_name AS itemList, item_id AS item_id FROM items WHERE user_id = ?");
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
    <link rel="stylesheet" href="../css/walletMobile.css"
        media="screen and (min-width: 320px)">
    <link rel="stylesheet" href="../css/walletDesktop.css"
      media="screen and (min-width: 1025px)">
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

        <div class="itemsButton">
            <button id="addButton">ADD</button>
            <button id="deleteButton">DELETE</button>
        </div>
        
        <div class="expensesBox">
            <ul class = "items">
                <?php
                    if (!empty($items) && !empty($prices)) {
                        for ($i = 0; $i < count($items); $i++) { //loop to access the array
                            echo "<li><span class='itemName'>{$items[$i]}</span>
                                    <span class='itemPrice'>{$prices[$i]}</span></li>";
                        }
                    } else {
                        echo "<p>No expenses</p>";
                    }
                ?>
            </ul>
        </div>

        <div class="savingsButton">
            <a href="savings.php">savings</a>
        </div>
    </div>

    <div id="setAllowance" style="display: none;" class="overlay">
        <button class="closeButton" id="closeButton">X</button>
        
        <div class="inputBox">
            <form action="../handlers/setAllowance.php" method="POST" class="allowanceForm">
                <label for="allowance">allowance</label>
                <input type="text" id="allowance" name="allowance">
                <button type="submit" id="setAllowanceButton">set</button>
                
            </form>
        </div>
        <p id="error-message-allowance" style="display: none;"></p>
    </div>

    <div id="addItem" style="display: none;" class="overlay">
        <button class="closeButton" id="closeButton">X</button>
        
        <div class="inputBox">
            <form action="../handlers/addItem.php" method="POST" class="addForm">
                <label for="item">add item</label>
                <input type="text" id="addItem" name="addItem">

                <label for="item" id="amountLabel">amount</label>
                <input type="text" id="addAmount" name="addAmount">

                <button type="submit" id="addItemButton">add</button>
            </form>
        </div>
        <p id="error-message-add" style="display: none; margin-top: 38vh;"></p>
    </div>

    <div id="deleteItem" style="display: none;" class="overlay">
        <button class="closeButton" id="closeButton">X</button>
        
        <div class="inputBox">
            <form action="../handlers/deleteItem.php" method="POST" class="deleteForm">
                <label for="item">delete item</label>
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
            if (errorType === "allowance") {
                let errorElement = document.getElementById("error-message-allowance");
                errorElement.textContent = errorMessage;
                errorElement.style.display = "block";
                document.getElementById("setAllowance").style.display = 'flex';
                activeDiv = document.getElementById("setAllowance");
            } else if (errorType === "add") {
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
</body>
</html>