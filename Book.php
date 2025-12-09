<?php

class Book {
    private int $id;
    private string $title;
    private float $price;
    private string $image;
    private int $categoryId;

    public function __construct($id, $title, $price, $image, $categoryId) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->image = $image;
        $this->categoryId = $categoryId;
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
    public function getCategoryId() { return $this->categoryId; }
}