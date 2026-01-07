<?php
require_once "../config.php";

if (!User::isLoggedIn() || !User::isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$bookModel = new Book();
$categoryModel = new Category();
$categories = $categoryModel->getAll();
$books = $bookModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/style.css">
  <title>Manage Books</title>
</head>
<body>
  <header class="topbar" role="banner">
    <nav class="nav" aria-label="Admin">
      <div class="brand">
        <div class="brand__mark" aria-hidden="true">📚</div>
        <div>
          <span class="brand__kicker">Admin</span>
          <span class="brand__title">Bookshop</span>
        </div>
      </div>
      <div class="nav__links" role="list">
        <a role="listitem" href="books.php" aria-current="page">Books</a>
        <a role="listitem" href="../index.php">Back to site</a>
      </div>
      <div class="loggedIn" aria-label="Signed in admin">
        <div class="user--avatar" aria-hidden="true"><img src="../img/pfp.jpeg" alt=""></div>
        <div>
          <h3 class="user--name"><?= htmlspecialchars($_SESSION['user']['name']) ?></h3>
          <span class="user--status">Admin</span>
        </div>
        <a class="btn btn--ghost" href="../auth/logout.php">Log out</a>
      </div>
    </nav>
  </header>

  <main class="section">
    <div class="section__head">
      <div>
        <p class="eyebrow">Catalog</p>
        <h1>Books</h1>
        <p class="section__hint">Add, edit or delete books.</p>
      </div>
      <a class="btn" href="book_create.php">Add book</a>
    </div>

    <div class="book-grid" role="list">
      <?php foreach ($books as $book): ?>
        <article class="book-card" role="listitem">
          <div class="book-card__cover">
            <img src="<?= htmlspecialchars($book['cover_image'] ?? '../img/placeholder.png') ?>" alt="Cover of <?= htmlspecialchars($book['title']) ?>">
          </div>
          <div class="book-card__body">
            <p class="book-card__category"><?= htmlspecialchars($book['category'] ?? 'Uncategorized') ?></p>
            <h3 class="book-card__title"><?= htmlspecialchars($book['title']) ?></h3>
            <p class="book-card__author">by <?= htmlspecialchars($book['author'] ?? 'Unknown author') ?></p>
            <div class="book-card__meta">
              <span class="book-card__price">€<?= number_format($book['price'], 2) ?></span>
              <span class="pill">Stock: <?= htmlspecialchars($book['stock'] ?? '—') ?></span>
            </div>
            <div class="book-card__actions">
              <a class="btn btn--small" href="book_edit.php?id=<?= $book['id'] ?>">Edit</a>
              <a class="btn btn--ghost btn--small" href="book_delete.php?id=<?= $book['id'] ?>">Delete</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
