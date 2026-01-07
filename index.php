<?php 
require_once "config.php";

// Auto-create default users if they don't exist
try {
    $user = new User();
    
    // Create admin user if not exists
    $user->createUser(
        'Admin User',
        'admin@admin.com',
        'Admin',
        'admin',
        1000.00
    );
    
    // Create regular user if not exists
    $user->createUser(
        'Regular User',
        'user@user.com',
        'User',
        'customer',
        100.00
    );
} catch (Exception $e) {
    // Users already exist or error, continue
}

// Check if user is logged in, otherwise redirect to login
if (!User::isLoggedIn()) {
    header("Location: auth/login.php");
    exit();
}

$book = new Book();
$books = $book->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style/style.css">
  <title>Bookshop</title>
</head>

<body>
  <header>
    <nav class="nav">
      <a href="index.php">Browse</a>
      <div class="loggedIn">
        <div class="user--avatar"><img src="./img/pfp.jpeg" alt=""></div>
        <div>
          <h3 class="user--name"><?= htmlspecialchars($_SESSION['user']['name']) ?></h3>
          <span class="user--status"><?= User::isAdmin() ? 'Admin' : 'Customer' ?></span>
        </div>
      </div>
      <a href="auth/logout.php">Log out</a>
    </nav>
  </header>

  <div class="books-container">
    <h1 class="page-title">Browse Our Books</h1>
    <div class="books-grid">
      <?php foreach ($books as $b): ?>
        <div class="book-card">
          <h3><?= htmlspecialchars($b['title']) ?></h3>
          <p>€<?= number_format($b['price'], 2) ?></p>
          <a href="book.php?id=<?= $b['id'] ?>">View Details</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</body>

</html>