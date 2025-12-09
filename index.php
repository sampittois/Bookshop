<?php
require "Book.php";
require "BookRepository.php";
require "CategoryRepository.php";
require "AuthorRepository.php";
require "HomeController.php";

// database connectie
$pdo = new PDO(
    "mysql:host=localhost;dbname=bookshop;charset=utf8mb4",
    "root",
    ""
);

$bookRepo = new BookRepository($pdo);
$categoryRepo = new CategoryRepository($pdo);
$authorRepo = new AuthorRepository($pdo);

$controller = new HomeController($bookRepo, $categoryRepo, $authorRepo);


// laad de homepage
$controller->index();