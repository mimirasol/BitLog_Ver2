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
  <div class="sidebar">
    <button class="logo">
      <img src="../css/assets/bitlog_coinanimation.gif" alt="Logo">
    </button>
    <button class="icon">
      <img src="../css/assets/home_icon.png" alt="Home">
    </button>
    <button class="icon">
      <img src="../css/assets/wallet_icon.png" alt="Wallet">
    </button>
    <button class="icon">
      <img src="../css/assets/profile_icon.png" alt="Profile">
    </button>
    <button class="icon logout">
      <img src="../css/assets/logout_icon.png" alt="Logout">
    </button>
  </div>

  <div class="content">
    <div class="character-wrapper">
      <img src="../css/assets/oreo.png" alt="Character">
      <div class="overlay-text">Hello, </div>
    </div>
  </div>

</body>
</html>