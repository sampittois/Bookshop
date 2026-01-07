<?php
class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function create($userId, $cart) {
        $this->db->beginTransaction();

        $total = 0;
        foreach ($cart as $bookId => $qty) {
            $stmt = $this->db->prepare("SELECT price FROM books WHERE id=?");
            $stmt->execute([$bookId]);
            $price = $stmt->fetchColumn();
            $total += $price * $qty;
        }

        $stmt = $this->db->prepare("
            INSERT INTO orders (user_id, total_price, status)
            VALUES (?, ?, 'paid')
        ");
        $stmt->execute([$userId, $total]);
        $orderId = $this->db->lastInsertId();

        foreach ($cart as $bookId => $qty) {
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, book_id, quantity, price_each)
                VALUES (?, ?, ?, (SELECT price FROM books WHERE id=?))
            ");
            $stmt->execute([$orderId, $bookId, $qty, $bookId]);
        }

        $this->db->commit();
        return $total;
    }
}
