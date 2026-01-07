<?php
require_once "config.php";

if (!User::isLoggedIn()) {
    header("Location: auth/login.php");
    exit();
}

$bookModel = new Book();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $bookId = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;

    if ($action === 'remove' && $bookId > 0) {
        Cart::remove($bookId);
        $flash = 'Removed from cart.';
    }

    if ($action === 'update' && $bookId > 0) {
        $qty = (int) ($_POST['quantity'] ?? 1);
        Cart::setQuantity($bookId, max(1, $qty));
        $flash = 'Updated quantity.';
    }

    if ($action === 'checkout') {
        $cart = Cart::get();
        if (!empty($cart)) {
        $order = new Order();
        try {
          $order->create($_SESSION['user']['id'], $cart);
          Cart::clear();
          $flash = 'Order placed!';
        } catch (Exception $e) {
          $flash = $e->getMessage();
        }
        } else {
            $flash = 'Your cart is empty.';
        }
    }
}

$cart = Cart::get();
$books = [];

if (!empty($cart)) {
  $books = $bookModel->getByIds(array_keys($cart));
}

function coverOrPlaceholder(?string $cover): string {
    return $cover && trim($cover) !== '' ? $cover : './img/placeholder.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style/style.css">
  <title>Cart · Bookshop</title>
</head>
<body>
  <header class="topbar" role="banner">
    <nav class="nav" aria-label="Primary">
      <div class="brand">
        <div class="brand__mark" aria-hidden="true">📚</div>
        <div>
          <span class="brand__kicker">Curated Reads</span>
          <span class="brand__title">Bookshop</span>
        </div>
      </div>
      <div class="nav__links" role="list">
        <a role="listitem" href="index.php">Home</a>
        <a role="listitem" href="admin/books.php">Manage</a>
        <a role="listitem" href="cart.php" aria-current="page">Cart</a>
        <a role="listitem" href="orders.php">Orders</a>
      </div>
      <div class="loggedIn" aria-label="Signed in user">
        <div class="user--avatar" aria-hidden="true"><img src="./img/pfp.jpeg" alt=""></div>
        <div>
          <h3 class="user--name"><?= htmlspecialchars($_SESSION['user']['name']) ?></h3>
          <span class="user--status"><?= User::isAdmin() ? 'Admin' : 'Customer' ?></span>
        </div>
        <a class="btn btn--ghost" href="auth/change_password.php">Change password</a>
        <a class="btn btn--ghost" href="auth/logout.php">Log out</a>
      </div>
    </nav>
  </header>

  <main class="cart" aria-labelledby="cart-title">
    <header class="section__head">
      <div>
        <p class="eyebrow">Your picks</p>
        <h1 id="cart-title">Cart</h1>
        <p class="section__hint">Review items before checkout.</p>
      </div>
      <a class="btn btn--ghost" href="index.php">Continue browsing</a>
    </header>

    <?php if ($flash): ?>
      <div class="pill" role="status"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
      <section class="empty-state" role="status">
        <p>Your cart is empty.</p>
        <a class="btn" href="index.php">Find books</a>
      </section>
    <?php else: ?>
      <section class="cart__layout">
        <div class="cart__list" role="list">
          <?php
            $total = 0;
            foreach ($books as $book):
              $qty = $cart[$book['id']] ?? 0;
              if ($qty <= 0) continue;
              $lineTotal = $book['price'] * $qty;
              $total += $lineTotal;
          ?>
            <article class="cart__item" role="listitem">
              <div class="cart__thumb">
                <img src="<?= htmlspecialchars(coverOrPlaceholder($book['cover_image'] ?? null)) ?>" alt="Cover of <?= htmlspecialchars($book['title']) ?>">
              </div>
              <div class="cart__info">
                <h2 class="cart__title"><?= htmlspecialchars($book['title']) ?></h2>
                <p class="book-card__author">by <?= htmlspecialchars($book['author'] ?? 'Unknown author') ?></p>
                <p class="book-card__category">Category: <?= htmlspecialchars($book['category'] ?? 'Uncategorized') ?></p>
                <div class="cart__meta">
                  <span class="pill">Price: €<?= number_format($book['price'], 2) ?></span>
                  <span class="pill">Line: €<?= number_format($lineTotal, 2) ?></span>
                </div>
              </div>
              <div class="cart__actions">
                <form method="post" class="inline-form">
                  <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                  <input type="hidden" name="action" value="update">
                  <label class="sr-only" for="qty-<?= $book['id'] ?>">Quantity</label>
                  <input id="qty-<?= $book['id'] ?>" type="number" name="quantity" min="1" value="<?= $qty ?>">
                  <button class="btn btn--outline btn--small" type="submit">Update</button>
                </form>
                <form method="post" class="inline-form">
                  <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                  <input type="hidden" name="action" value="remove">
                  <button class="btn btn--ghost btn--small" type="submit">Remove</button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <aside class="cart__summary">
          <h2>Summary</h2>
          <div class="cart__summary-row">
            <span>Items</span>
            <span><?= array_sum($cart) ?></span>
          </div>
          <div class="cart__summary-row">
            <span>Total</span>
            <strong>€<?= number_format($total, 2) ?></strong>
          </div>
          <form method="post">
            <input type="hidden" name="action" value="checkout">
            <button class="btn" type="submit" <?= $total <= 0 ? 'disabled' : '' ?>>Checkout</button>
          </form>
        </aside>
      </section>
    <?php endif; ?>
  </main>
</body>
</html>
