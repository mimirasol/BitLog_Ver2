<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $allowance = $_POST['allowance'];

    $stmt = $conn->prepare("SELECT allowance_id FROM allowances WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $update = $conn->prepare("UPDATE allowances SET amount = ? WHERE user_id = ?");
        $update->bind_param("si", $allowance, $user_id);
        
        if ($update->execute()) {
            header("location: ../views/wallet.php");
            exit();
        }
    } else {
        $set = $conn->prepare("INSERT INTO allowances (user_id, amount) VALUES (?,?)");
        $set->bind_param("is", $user_id, $allowance);

        if ($set->execute()) {
            header("location: ../views/wallet.php");
            exit();
        }
    }
}

?>