<?php

class HomeController {

    private BookRepository $bookRepo;

    public function __construct(BookRepository $bookRepo) {
        $this->bookRepo = $bookRepo;
    }

    public function index() {
        // haal boeken op via repository
        $books = $this->bookRepo->getLatestBooks();

        // laad de homepage view en geef boeken door
        include __DIR__ . "/views/home.php";
    }
}
