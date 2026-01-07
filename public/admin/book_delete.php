<?php
require_once "../config.php";

if (!User::isLoggedIn() || !User::isAdmin()) {
    header("Location: ../auth/login.php");
    exit();
}

$bookModel = new Book();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$book = $bookModel->getById($id);

if (!$book) {
    header("Location: books.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookModel->delete($id);
    header("Location: books.php");
    exit();
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
    <p class="section__hint">This action cannot be undone.</p>
    <form method="post">
      <button class="btn" type="submit">Confirm delete</button>
      <a class="btn btn--ghost" href="books.php">Cancel</a>
    </form>
  </main>
</body>
</html>
