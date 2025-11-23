<?php

require "Book.php";
require "BookRepository.php";
require "HomeController.php";

// database connectie
$pdo = new PDO(
    "mysql:host=localhost;dbname=Bookshop;charset=utf8mb4",
    "root",
    ""
);

$bookRepo = new BookRepository($pdo);
$controller = new HomeController($bookRepo);

// laad de homepage
$controller->index();

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookshop</title>
</head>
<body>
    
</body>
</html>