<?php
require_once "../config.php";

if (!User::isLoggedIn()) {
	header("Location: ../auth/login.php");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
	Cart::add((int) $_POST['book_id']);

	$redirect = $_SERVER['HTTP_REFERER'] ?? '../index.php';
	header("Location: $redirect");
	exit();
}

http_response_code(400);
echo "Invalid request";
