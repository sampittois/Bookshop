<?php
// Database connection
$pdo = new PDO(
  "mysql:host=localhost;dbname=bookshop;charset=utf8mb4",
  "root",
  ""
);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/style.css">
  <title>Bookshop</title>
</head>

<body>
  <header>
    <nav class="nav">
      <a href="?page=browser">Browse</a>
      <a href="#" class="loggedIn">
        <div class="user--avatar"><img src="./img/pfp.jpeg" alt=""></div>
        <h3 class="user--name">Username here</h3>
        <span class="user--status">Reading</span>
      </a>
      <a href="logout">Log out?</a>
    </nav>
  </header>


</body>

</html>