<?php
require_once "../config.php";

if (!User::isLoggedIn() || !User::isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$bookModel = new Book();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$book = $bookModel->getById($id);
$error = '';

if (!$book) {
    header("Location: books.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $bookModel->delete($id);
        header("Location: books.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/style.css">
  <title>Delete Book</title>
</head>
<body>
  <main class="detail">
    <h1>Delete "<?= htmlspecialchars($book['title']) ?>"?</h1>
    <p class="section__hint">This will permanently remove the book and all related data.</p>
    <?php if ($error): ?>
      <div class="pill" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <button class="btn" type="submit" <?= $error ? 'disabled' : '' ?>>Confirm delete</button>
      <a class="btn btn--ghost" href="books.php">Cancel</a>
    </form>
  </main>
</body>
</html>
