<?php
require_once "config.php";

if (!User::isLoggedIn()) {
    header("Location: auth/login.php");
    exit();
}

$orderModel = new Order();
$orders = $orderModel->getByUser($_SESSION['user']['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style/style.css">
  <title>Your Orders</title>
</head>
<body>
  <header class="topbar" role="banner">
    <nav class="nav" aria-label="Primary">
      <div class="brand">
        <div class="brand__mark" aria-hidden="true">📚</div>
        <div>
          <span class="brand__kicker">Orders</span>
          <span class="brand__title">Bookshop</span>
        </div>
      </div>
      <div class="nav__links" role="list">
        <a role="listitem" href="index.php">Home</a>
        <a role="listitem" href="cart.php">Cart</a>
        <a role="listitem" href="orders.php" aria-current="page">Orders</a>
      </div>
      <div class="loggedIn" aria-label="Signed in user">
        <div class="user--avatar" aria-hidden="true"><img src="./img/pfp.jpeg" alt=""></div>
        <div>
          <h3 class="user--name"><?= htmlspecialchars($_SESSION['user']['name']) ?></h3>
          <span class="user--status"><?= User::isAdmin() ? 'Admin' : 'Customer' ?></span>
        </div>
        <a class="btn btn--ghost" href="auth/logout.php">Log out</a>
      </div>
    </nav>
  </header>

  <main class="section">
    <div class="section__head">
      <div>
        <p class="eyebrow">History</p>
        <h1>Your orders</h1>
        <p class="section__hint">Recently placed orders.</p>
      </div>
      <a class="btn btn--ghost" href="index.php">Back</a>
    </div>

    <?php if (empty($orders)): ?>
      <div class="empty-state" role="status">
        <p>No orders yet.</p>
        <a class="btn" href="index.php">Find books</a>
      </div>
    <?php else: ?>
      <div class="cart__list" role="list">
        <?php foreach ($orders as $order): ?>
          <article class="cart__item" role="listitem">
            <div class="cart__info">
              <h2 class="cart__title">Order #<?= $order['id'] ?></h2>
              <p class="book-card__category">Status: <?= htmlspecialchars($order['status'] ?? 'unknown') ?></p>
              <p class="book-card__author">Total: €<?= number_format($order['total_price'], 2) ?></p>
              <p class="section__hint">Placed: <?= htmlspecialchars($order['created_at'] ?? '') ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
