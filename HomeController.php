<?php
require_once "BookRepository.php";
require_once "CategoryRepository.php";
require_once "AuthorRepository.php";

class HomeController {
    private BookRepository $bookRepo;
    private CategoryRepository $categoryRepo;
    private AuthorRepository $authorRepo;

    public function __construct(BookRepository $bookRepo, CategoryRepository $categoryRepo, AuthorRepository $authorRepo) {
        $this->bookRepo = $bookRepo;
        $this->categoryRepo = $categoryRepo;
        $this->authorRepo = $authorRepo;
    }

    public function index() {

        $selectedCategory = !empty($_GET['category']) ? (int)$_GET['category'] : null;
        $search           = $_GET['search'] ?? null;
        $sort             = $_GET['sort'] ?? null;

        // Load filtered books (search, category, sort)
        $books = $this->bookRepo->getFilteredBooks($selectedCategory, $search, $sort);

        // Attach authors
        $books = $this->bookRepo->attachAuthors($books, $this->authorRepo);

        // Load categories for filter dropdown
        $categories = $this->categoryRepo->getAllCategories();

        // Load the view
        require "views/home.php";
    }
}
