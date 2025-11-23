<?php
require_once "BookRepository.php";
require_once "CategoryRepository.php";

class HomeController {
    private BookRepository $bookRepo;
    private CategoryRepository $categoryRepo;

    public function __construct(BookRepository $bookRepo, CategoryRepository $categoryRepo) {
        $this->bookRepo = $bookRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function index() {
        $selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;

        $books = $this->bookRepo->getLatestBooks(6, $selectedCategory);
        $categories = $this->categoryRepo->getAllCategories();

        require "views/home.php";
    }
}