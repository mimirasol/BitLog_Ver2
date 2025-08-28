<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_SESSION["username"];
    $editUsername = $_POST["editUsername"];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $editUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("Location: ../views/profile.php?error=" . urlencode("Username already exist!"));
        exit();
    } else {
        $stmt->close();

        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->bind_param ("ss", $editUsername, $username); //updates the username in the database

        if ($stmt->execute()) {
            $_SESSION["username"] = $editUsername; //updates the session username

            header("Location: ../views/profile.php?username=" . urlencode("Username updated!"));
            exit();
        }
    }
}

?>

