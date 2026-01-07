<?php
require_once "../config.php";

// Read query params
$category = isset($_GET['category']) && $_GET['category'] !== '' ? (int) $_GET['category'] : null;
$search = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
$sort = isset($_GET['sort']) ? (string) $_GET['sort'] : '';

$bookModel = new Book();
$books = $bookModel->getFiltered($category, $search, $sort);

function coverOrPlaceholder(?string $cover): string {
    return $cover && trim($cover) !== '' ? $cover : '../img/placeholder.png';
}

header('Content-Type: text/html; charset=UTF-8');

if (empty($books)) {
    echo '<div class="empty-state" role="status">';
    echo '<p>No books match your filters yet. Try clearing the search or picking a different category.</p>';
    echo '<a class="btn btn--ghost" href="../index.php">Reset filters</a>';
    echo '</div>';
    exit;
}

echo '<div class="book-grid" role="list">';
foreach ($books as $book) {
    $id = htmlspecialchars((string) $book['id']);
    $title = htmlspecialchars($book['title'] ?? '');
    $author = htmlspecialchars($book['author'] ?? 'Unknown author');
    $categoryName = htmlspecialchars($book['category'] ?? 'Uncategorized');
    $desc = htmlspecialchars($book['description'] ?? 'No description available yet.');
    $price = number_format((float) ($book['price'] ?? 0), 2);
    $stock = htmlspecialchars((string) ($book['stock'] ?? '—'));
    $cover = htmlspecialchars(coverOrPlaceholder($book['cover_image'] ?? null));

    echo '<article class="book-card" role="listitem">';
    echo '  <div class="book-card__cover">';
    echo '    <img src="' . $cover . '" alt="Cover of ' . $title . '" loading="lazy">';
    echo '  </div>';
    echo '  <div class="book-card__body">';
    echo '    <p class="book-card__category">' . $categoryName . '</p>';
    echo '    <h3 class="book-card__title">' . $title . '</h3>';
    echo '    <p class="book-card__author">by ' . $author . '</p>';
    echo '    <p class="book-card__desc">' . $desc . '</p>';
    echo '    <div class="book-card__meta">';
    echo '      <span class="book-card__price">€' . $price . '</span>';
    echo '      <span class="pill">In stock: ' . $stock . '</span>';
    echo '    </div>';
    echo '    <div class="book-card__actions">';
    echo '      <a class="btn btn--small" href="../book.php?id=' . $id . '">View details</a>';
    echo '      <form method="post" action="addToCart.php" class="inline-form">';
    echo '        <input type="hidden" name="book_id" value="' . $id . '">';
    echo '        <button class="btn btn--outline btn--small" type="submit">Add to cart</button>';
    echo '      </form>';
    echo '    </div>';
    echo '  </div>';
    echo '</article>';
}
echo '</div>';

// Include database configuration
include('../config.php');

// Get the category filter from the request
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query to filter books by category
$sql = "SELECT * FROM books";
if ($category) {
    $sql .= " WHERE category = :category";
}

// Prepare and execute the statement
$stmt = $pdo->prepare($sql);
if ($category) {
    $stmt->bindParam(':category', $category);
}
$stmt->execute();

// Fetch the results
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($books);
