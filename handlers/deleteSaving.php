<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $selectedItem = $_POST['itemsDropdown'];

    if (empty($selectedItem)) {
        header("Location: ../views/savings.php?error=Enter+a+valid+item&type=delete");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM savings where goal_id = ? AND user_id = ?"); 
    $stmt->bind_param("ii", $selectedItem, $user_id);
    
    if ($stmt->execute()) {
        header("location: ../views/savings.php");
        exit();
    } else {
        header("location: ../views/savings.php?error=error delete (items)&type=delete");
        exit();
    }
}
?>