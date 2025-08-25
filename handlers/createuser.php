<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        die("ERROR: All fields are required!");
    }

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../views/signup.php?error=" . urlencode("User already exist!"));
        exit();
        $stmt->close();
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES(?,?)");
        if (!$stmt) {
            die("Error preparing insert statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $username, $password_hash);

        if($stmt->execute()) {
             echo "<script>
                alert('Account successfully created! Redirecting to log in...');
                window.location.href = '../views/login.php';
            </script>";
            exit();
        } else {
            $error = "Error inserting user: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>