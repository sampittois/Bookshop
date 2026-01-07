<?php
require_once "../config.php";

if (!User::isLoggedIn()) {
    header("Location: ../auth/login.php");
    exit;
}

$bookId = (int) ($_POST['book_id'] ?? 0);
$rating = (int) ($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($bookId > 0 && $rating >= 1 && $rating <= 5 && $comment !== '') {
    $review = new Review();
    $review->add(
        $_SESSION['user']['id'],
        $bookId,
        $rating,
        htmlspecialchars($comment)
    );
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '../book.php?id=' . $bookId;
header("Location: $redirect");
exit;
