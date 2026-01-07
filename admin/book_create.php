<?php
require_once "../config.php";

if (!User::isLoggedIn() || !User::isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$categoryModel = new Category();
$bookModel = new Book();
$categories = $categoryModel->getAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = (int) ($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $cover = trim($_POST['cover_image'] ?? '');
    $stock = (int) ($_POST['stock'] ?? 0);

    // Normalize cover image path to always be img/filename.ext
    if (!empty($cover)) {
        $cover = preg_replace('#^\\.{1,2}/#', '', $cover);
        if (strpos($cover, 'img/') !== 0 && strpos($cover, 'http://') !== 0 && strpos($cover, 'https://') !== 0) {
            $cover = 'img/' . $cover;
        }
    }

    if ($category <= 0) $errors[] = "Select a category.";
    if ($title === '') $errors[] = "Title is required.";
    if ($price <= 0) $errors[] = "Price must be greater than 0.";
    if ($stock < 0) $errors[] = "Stock cannot be negative.";

    if (!$errors) {
        $bookModel->create([$category, $title, $description, $price, $cover, $stock, $author]);
        header("Location: books.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/style.css">
  <title>Add Book</title>
</head>
<body>
  <main class="detail">
    <h1>Add book</h1>
    <?php foreach ($errors as $err): ?>
      <div class="pill" role="alert"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>
    <form method="post" class="form form--vertical">
      <label>Category
        <select name="category_id" required>
          <option value="">Select...</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= (isset($category) && $category == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Title
        <input type="text" name="title" required value="<?= htmlspecialchars($title ?? '') ?>">
      </label>
      <label>Author
        <input type="text" name="author" value="<?= htmlspecialchars($author ?? '') ?>">
      </label>
      <label>Description
        <textarea name="description" rows="4"><?= htmlspecialchars($description ?? '') ?></textarea>
      </label>
      <label>Price
        <input type="number" step="0.01" min="0" name="price" required value="<?= htmlspecialchars($price ?? '') ?>">
      </label>
      <label>Cover image URL
        <input type="text" name="cover_image" value="<?= htmlspecialchars($cover ?? '') ?>">
      </label>
      <label>Stock
        <input type="number" min="0" name="stock" required value="<?= htmlspecialchars($stock ?? 0) ?>">
      </label>
      <button class="btn" type="submit">Create</button>
      <a class="btn btn--ghost" href="books.php">Cancel</a>
    </form>
  </main>
</body>
</html>
