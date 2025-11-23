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
