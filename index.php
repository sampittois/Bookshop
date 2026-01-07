<?php
require_once "config.php";

// Auto-create default users if they don't exist
try {
  $user = new User();

  $user->createUser(
    'Admin User',
    'admin@admin.com',
    'Admin',
    'admin',
    1000.00
  );

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

if (!User::isLoggedIn()) {
  header("Location: auth/login.php");
  exit();
}

$db = Database::connect();
$categoryModel = new Category();
$bookModel = new Book();

$selectedCategory = (isset($_GET['category']) && $_GET['category'] !== '') ? (int) $_GET['category'] : null;
$searchTerm = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? '';

$categories = $categoryModel->getAll();

$libraryBooks = $bookModel->getFiltered($selectedCategory, $searchTerm, $sort);

$newArrivals = $bookModel->getNewArrivals(6);

function coverOrPlaceholder(?string $cover): string
{
  return $cover && trim($cover) !== '' ? $cover : './img/placeholder.png';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style/style.css">
  <title>Bookshop · Home</title>
</head>

<body>
  <header class="topbar" role="banner">
    <nav class="nav" aria-label="Primary">
      <div class="brand">
        <div>
          <span class="brand__kicker">Nook & Novel</span>
          <span class="brand__title">Bookshop</span>
        </div>
      </div>
      <div class="nav__links" role="list">
        <a role="listitem" href="index.php" aria-current="page">Home</a>
        <a role="listitem" href="admin/books.php">Manage</a>
        <a role="listitem" href="cart.php">Cart</a>
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

  <main>
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero__copy">
        <p class="eyebrow">Fresh on the shelves</p>
        <h1 id="hero-title">Discover books that keep you turning the page.</h1>
        <p class="lede">Browse new arrivals, filter by category, search by title, and sort by what matters to you.</p>
        <div class="hero__actions">
          <a class="btn" href="#library" aria-label="Jump to the library">Browse library</a>
          <a class="btn btn--ghost" href="#new">See new arrivals</a>
        </div>
      </div>
      <div class="hero__stat" aria-live="polite">
        <span class="stat__label">Books available</span>
        <span class="stat__value"><?= count($libraryBooks) ?></span>
        <span class="stat__hint">Filtered in real time</span>
      </div>
    </section>

    <section id="new" class="section" aria-labelledby="new-title">
      <div class="section__head">
        <div>
          <p class="eyebrow">Just in</p>
          <h2 id="new-title">New arrivals</h2>
          <p class="section__hint">Handpicked highlights — updated automatically.</p>
        </div>
        <div class="section__pill">Showing <?= count($newArrivals) ?> books</div>
      </div>
      <div class="carousel" role="list">
        <?php foreach ($newArrivals as $book): ?>
          <article class="book-card" role="listitem">
            <div class="book-card__cover">
              <img
                src="<?= htmlspecialchars(coverOrPlaceholder($book['cover_image'] ?? null)) ?>"
                alt="Cover of <?= htmlspecialchars($book['title']) ?>"
                loading="lazy">
            </div>
            <div class="book-card__body">
              <p class="book-card__category"><?= htmlspecialchars($book['category'] ?? 'Uncategorized') ?></p>
              <h3 class="book-card__title"><?= htmlspecialchars($book['title']) ?></h3>
              <p class="book-card__author">by <?= htmlspecialchars($book['author'] ?? 'Unknown author') ?></p>
              <div class="book-card__meta">
                <span class="badge">New</span>
                <span class="book-card__price">€<?= number_format($book['price'], 2) ?></span>
              </div>
              <a class="btn btn--small" href="book.php?id=<?= $book['id'] ?>">View details</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section id="filter" class="filters" aria-label="Library filters">
      <form class="filter" method="get" action="">
        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <label for="category">Category</label>
        <select id="category" name="category" onchange="this.form.submit()">
          <option value="">All categories</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category['id']) ?>" <?= $selectedCategory === (int) $category['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($category['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>

      <form class="filter filter--wide" method="get" action="">
        <input type="hidden" name="category" value="<?= $selectedCategory !== null ? (int) $selectedCategory : '' ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <label class="sr-only" for="search">Search by title</label>
        <div class="input-group">
          <span aria-hidden="true">🔍</span>
          <input
            id="search"
            name="search"
            type="text"
            value="<?= htmlspecialchars($searchTerm) ?>"
            placeholder="Search by title..."
            aria-label="Search books by title">
        </div>
        <button class="btn" type="submit">Search</button>
      </form>

      <form class="filter" method="get" action="">
        <input type="hidden" name="category" value="<?= $selectedCategory !== null ? (int) $selectedCategory : '' ?>">
        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
        <label for="sort">Sort</label>
        <select id="sort" name="sort" onchange="this.form.submit()">
          <option value="">Title (A-Z)</option>
          <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Title (Z-A)</option>
          <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price (Low-High)</option>
          <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price (High-Low)</option>
          <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Title (A-Z)</option>
        </select>
      </form>
    </section>

    <section id="library" class="section" aria-labelledby="library-title">
      <div class="section__head">
        <div>
          <p class="eyebrow">Full library</p>
          <h2 id="library-title">Browse everything</h2>
          <p class="section__hint">Filtered by your criteria — sorted <?= $sort ? htmlspecialchars($sort) : 'A-Z' ?>.</p>
        </div>
        <div class="section__pill"><?= count($libraryBooks) ?> titles</div>
      </div>

      <?php if (empty($libraryBooks)): ?>
        <div class="empty-state" role="status">
          <p>No books match your filters yet. Try clearing the search or picking a different category.</p>
          <a class="btn btn--ghost" href="index.php">Reset filters</a>
        </div>
      <?php else: ?>
        <div class="book-grid" role="list">
          <?php foreach ($libraryBooks as $book): ?>
            <article class="book-card" role="listitem">
              <div class="book-card__cover">
                <img
                  src="<?= htmlspecialchars(coverOrPlaceholder($book['cover_image'] ?? null)) ?>"
                  alt="Cover of <?= htmlspecialchars($book['title']) ?>"
                  loading="lazy">
              </div>
              <div class="book-card__body">
                <p class="book-card__category"><?= htmlspecialchars($book['category'] ?? 'Uncategorized') ?></p>
                <h3 class="book-card__title"><?= htmlspecialchars($book['title']) ?></h3>
                <p class="book-card__author">by <?= htmlspecialchars($book['author'] ?? 'Unknown author') ?></p>
                <p class="book-card__desc"><?= htmlspecialchars($book['description'] ?? 'No description available yet.') ?></p>
                <div class="book-card__meta">
                  <span class="book-card__price">€<?= number_format($book['price'], 2) ?></span>
                  <span class="pill">In stock: <?= htmlspecialchars($book['stock'] ?? '—') ?></span>
                </div>
                <div class="book-card__actions">
                  <a class="btn btn--small" href="book.php?id=<?= $book['id'] ?>">View details</a>
                  <form method="post" action="ajax/addToCart.php" class="inline-form">
                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                    <button class="btn btn--outline btn--small" type="submit">Add to cart</button>
                  </form>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>

</html>