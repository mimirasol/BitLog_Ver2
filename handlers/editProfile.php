<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $selectedAnimal = $_POST['selectedAnimal'];
    $username = $_SESSION["username"];

    $stmt = $conn->prepare("UPDATE users SET character_img = ? WHERE username = ?");
    $stmt->bind_param ("ss", $selectedAnimal, $username);

    if ($stmt->execute()) {
        header("Location: ../views/profile.php");
        exit();
    }
}

?>