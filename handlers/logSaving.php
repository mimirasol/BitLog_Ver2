<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $goal_id = $_POST['goal_id'];
    $amount = $_POST['amount'];

    if (empty($amount) || !is_numeric($amount)) {
        header("Location: ../views/savings.php?error=Enter+a+valid+amount&type=amount");
        exit();
    }

    $update = $conn->prepare("UPDATE savings SET current_amount = current_amount + ? WHERE user_id = ? AND goal_id = ?");
    $update->bind_param("iii", $amount, $user_id, $goal_id);
    
    if ($update->execute()) {
        header("location: ../views/savings.php");
        exit();
    } else {
        header("Location: ../views/savings.php?error=Error+updating+savings&type=amount");
        exit();
    }
}
?>