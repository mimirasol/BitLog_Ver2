<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($stmt = $conn->prepare("SELECT user_id, username, password, role, character_img FROM users WHERE username = ?")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_username, $db_password, $role, $db_animal);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $db_username;
                $_SESSION["role"] = $role;

                if ($role === "user") {
                    $_SESSION['just_logged_in'] = true;
                    header("Location: ../views/dashboard.php");
                    exit();
                } else {
                    header("Location: ../views/login.php?error=" . urlencode("Invalid role assigned!"));
                    exit();
                }
                exit();
            } else {
                header("Location: ../views/login.php?error=" . urlencode("Invalid password!"));
                exit();
            }
        } else {
            header("Location: ../views/login.php?error=" . urlencode("User does not exist!"));
            exit();
        }

        $stmt->close();
    } else {
        header("Location: ../views/login.php?error=" . urlencode("Database query failed: " . $conn->error));
        exit();
    }
}

$conn->close();
?>
