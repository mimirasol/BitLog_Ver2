<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $addItem = $_POST['addItem'];
    $addAmount = $_POST['addAmount'];

    if (empty($addAmount) || !is_numeric($addAmount)) {
        header("Location: ../views/wallet.php?error=Enter+a+valid+amount&type=add");
        exit();
    }

    if (empty($addItem)) {
        header("Location: ../views/wallet.php?error=Enter+a+valid+item&type=add");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO items (user_id, item_name) VALUES (?,?)");
    $stmt->bind_param("is", $user_id, $addItem);

    if ($stmt->execute()) {
        $item_id = $stmt->insert_id; // gets the last inserted item_id

        $amount = $conn->prepare("INSERT INTO expenses (item_id, amount) VALUES (?,?)");
        $amount->bind_param("is", $item_id, $addAmount);
        
        if ($amount->execute()) {
            header("location: ../views/wallet.php");
            exit();
        } else {
            header("location: ../views/wallet.php?error=error adding (expenses)&type=add");
            exit();
        }
    } else {
        header("location: ../views/wallet.php?error=error adding (items)&type=add");
        exit();
    }
}


?>