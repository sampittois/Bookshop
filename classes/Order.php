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
            $stmt = $this->db->prepare("SELECT price, stock FROM books WHERE id=?");
            $stmt->execute([$bookId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new RuntimeException("Book not found");
            }
            if ($row['price'] <= 0) {
                throw new RuntimeException("Invalid price");
            }
            if ($row['stock'] < $qty) {
                throw new RuntimeException("Insufficient stock");
            }
            $total += $row['price'] * $qty;
        }

        $userStmt = $this->db->prepare("SELECT balance FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $balance = (float) $userStmt->fetchColumn();
        if ($balance < $total) {
            throw new RuntimeException("Insufficient balance");
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

            $updateStock = $this->db->prepare("UPDATE books SET stock = stock - ? WHERE id = ?");
            $updateStock->execute([$qty, $bookId]);
        }

        $deduct = $this->db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $deduct->execute([$total, $userId]);

        $this->db->commit();
        return $total;
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
