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
    <div class="siderbar-items">
      <button class="icon">
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
    </div>
  <button class="icon logout">
    <img src="../css/assets/logout_icon.png" alt="Logout">
  </button>
  </div>

  <div class="content">
    <h2>Responsive Sidebar Example</h2>
    <p>This example use media queries to transform the sidebar to a top navigation bar when the screen size is 700px or less.</p>
    <p>We have also added a media query for screens that are 400px or less, which will vertically stack and center the navigation links.</p>
    <h3>Resize the browser window to see the effect.</h3>
  </div>

</body>
</html>