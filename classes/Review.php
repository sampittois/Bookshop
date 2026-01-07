<?php
class Review {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function add($userId, $bookId, $rating, $comment) {
        $stmt = $this->db->prepare("
            INSERT INTO reviews (user_id, book_id, rating, comment)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $bookId, $rating, $comment]);
    }
}
