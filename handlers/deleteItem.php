<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $selectedItem = $_POST['itemsDropdown'];

    if (empty($selectedItem)) {
        header("Location: ../views/wallet.php?error=Enter+a+valid+item&type=delete");
        exit();
    }

    $stmt = $conn->prepare("DELETE e FROM expenses e JOIN items i  ON e.item_id = i.item_id WHERE item_name = ? AND user_id = ?");
    $stmt->bind_param("si", $selectedItem, $user_id);

    if ($stmt->execute()) {
        $stmt = $conn->prepare("DELETE FROM items where item_name = ? AND user_id = ?"); 
        $stmt->bind_param("si", $selectedItem, $user_id);
        
        if ($stmt->execute()) {
            header("location: ../views/wallet.php");
            exit();
        } else {
            header("location: ../views/wallet.php?error=error delete (items)&type=delete");
            exit();
        }
    } else {
        header("location: ../views/wallet.php?error=error delete (expenses)&type=delete");
        exit();
    }
}

?>