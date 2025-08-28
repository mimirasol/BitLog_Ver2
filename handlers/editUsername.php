<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_SESSION["username"];
    $editUsername = $_POST["editUsername"];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../views/profile.php?error=" . urlencode("User already exist!"));
        exit();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->bind_param ("ss", $editUsername, $username); //updates the username in the database

        if ($stmt->execute()) {
            $_SESSION["username"] = $editUsername; //updates the session username

            header("Location: ../views/profile.php");
            exit();
        }
    }
}

?>

