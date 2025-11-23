<?php

class Book {
    private int $id;
    private string $title;
    private float $price;
    private string $image;

    public function __construct($id, $title, $price, $image) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->image = $image;
    }

    public function getId() { return $id; }
    public function getTitle() { return $this->title; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
}
