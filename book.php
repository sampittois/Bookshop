<?php
require_once "config.php";

if (!User::isLoggedIn()) {
    header("Location: auth/login.php");
    exit();
}


$bookModel = new Book();
$bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'], $_POST['book_id'])) {
    Cart::add((int) $_POST['book_id']);
    $cartMessage = "Added to your cart.";
}
$book = $bookModel->getById($bookId);

if (!$book) {
    http_response_code(404);
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
  <title><?= $book ? htmlspecialchars($book['title']) . ' · Bookshop' : 'Book not found' ?></title>
</head>
<body>
  <header class="topbar" role="banner">
    <nav class="nav" aria-label="Primary">
      <div class="brand">
        <div class="brand__mark" aria-hidden="true"><img class="logo" src="./img/logo.png" alt=""></div>
        <div>
          <span class="brand__kicker">Curated Reads</span>
          <span class="brand__title">Bookshop</span>
        </div>
      </div>
      <div class="nav__links" role="list">
        <a role="listitem" href="index.php">Home</a>
        <a role="listitem" href="admin/books.php">Manage</a>
        <a role="listitem" href="cart.php">Cart</a>
        <a role="listitem" href="orders.php">Orders</a>
        <a role="listitem" href="#details" aria-current="page">Details</a>
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

  <main class="detail" id="details">
    <?php if (!$book): ?>
      <section class="empty-state" role="status">
        <h1>Book not found</h1>
        <p>The book you are looking for does not exist or was removed.</p>
        <a class="btn" href="index.php">Back to library</a>
      </section>
    <?php else: ?>
      <section class="detail__layout">
        <div class="detail__cover">
          <img src="<?= htmlspecialchars(coverOrPlaceholder($book['cover_image'] ?? null)) ?>" alt="Cover of <?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="detail__body">
          <p class="book-card__category"><?= htmlspecialchars($book['category'] ?? 'Uncategorized') ?></p>
          <h1 class="detail__title"><?= htmlspecialchars($book['title']) ?></h1>
          <p class="book-card__author">by <?= htmlspecialchars($book['author_names'] ?? 'Unknown author') ?></p>
          <p class="detail__description"><?= htmlspecialchars($book['description'] ?? 'No description available yet.') ?></p>
          <div class="detail__meta">
            <span class="pill">Stock: <?= htmlspecialchars($book['stock'] ?? '—') ?></span>
            <span class="pill">Price: €<?= number_format($book['price'], 2) ?></span>
            <?php if (!empty($book['category'])): ?>
              <span class="pill">Category: <?= htmlspecialchars($book['category']) ?></span>
            <?php endif; ?>
          </div>
          <?php if (!empty($cartMessage)): ?>
            <div class="pill"><?= htmlspecialchars($cartMessage) ?></div>
          <?php endif; ?>
          <div class="detail__actions">
            <form method="post" class="inline-form">
              <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
              <button type="submit" name="add_to_cart" class="btn">Add to cart</button>
            </form>
            <a class="btn btn--ghost" href="index.php">Back to library</a>
          </div>

          <form method="post" action="ajax/addReview.php" class="form--vertical" style="margin-top:12px;">
            <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
            <label>Rating
              <input type="number" name="rating" min="1" max="5" value="5" required>
            </label>
            <label>Comment
              <textarea name="comment" rows="3" placeholder="Write a short review" required></textarea>
            </label>
            <button class="btn btn--outline" type="submit">Submit review</button>
          </form>
        </div>
      </section>
    <?php endif; ?>
  </main>
</body>
</html>
