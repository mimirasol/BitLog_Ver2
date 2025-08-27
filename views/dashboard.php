<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboardDesktop.css"
      media="screen and (min-width: 1441px)">
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <button class="icon">
        <img src="../css/assets/home_icon.png" alt="Home">
      </button>
      <button class="icon">
        <img src="../css/assets/wallet_icon.png" alt="Wallet">
      </button>
      <button class="icon">
        <img src="../css/assets/profile_icon.png" alt="Profile">
      </button>
      <button class="icon">
        <img src="../css/assets/logout_icon.png" alt="Logout">
      </button>
    </div>
  </div>

  <div class="main">
    <h1>Dashboard Content</h1>
    <p>This is where your dashboard content will appear.</p>
  </div>

</body>
</html>