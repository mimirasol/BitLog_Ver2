<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $selectedAnimal = $_POST['selectedAnimal'];
    $user_id = $_SESSION["user_id"];
    $editUsername = $_POST["editUsername"];

    $stmt = $conn->prepare("UPDATE users SET character_img = ? WHERE user_id = ?");
    $stmt->bind_param ("ss", $selectedAnimal, $user_id);

    if ($stmt->execute()) {
        header("Location: ../views/profile.php");
        exit();
    }
}

?>