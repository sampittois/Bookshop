<?php
require_once "../config.php";

if (!User::isLoggedIn()) {
    echo json_encode(["success" => false]);
    exit;
}

$review = new Review();
$review->add(
    $_SESSION['user']['id'],
    $_POST['book_id'],
    $_POST['rating'],
    htmlspecialchars($_POST['comment'])
);

echo json_encode(["success" => true]);
