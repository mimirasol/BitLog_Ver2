<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $clear = $_POST['confirmClear'];

    if (empty($clear)) {
        header("Location: ../views/wallet.php?error=No+items&type=delete");
        exit();
    }

    if ($clear == "yes") {
        $stmt = $conn->prepare("DELETE e 
                                FROM expenses e
                                INNER JOIN items i ON e.item_id = i.item_id
                                WHERE i.user_id = ?
                            ");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("DELETE FROM items where user_id = ?"); 
            $stmt->bind_param("i", $user_id);
            
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
    } else {
        header("location: ../views/wallet.php");
        exit();
    }
}

?>