<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $addItem = $_POST['addItem'];
    $addAmount = $_POST['addAmount'];

    if (empty($addAmount) || !is_numeric($addAmount)) {
        header("Location: ../views/savings.php?error=Enter+a+valid+amount&type=add");
        exit();
    }

    if (empty($addItem)) {
        header("Location: ../views/savings.php?error=Enter+a+valid+item&type=add");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO savings (user_id, goal_name, target_amount) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $addItem, $addAmount);

    if ($stmt->execute()) {
        header("Location: ../views/savings.php");
        exit();
    } else {
        header("Location: ../views/savings.php?error=Error+adding+savings&type=add");
        exit();
    }
}
?>