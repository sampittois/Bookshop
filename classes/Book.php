<?php
class Book {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        return $this->db->query("
            SELECT books.*, categories.name AS category
            FROM books
            JOIN categories ON books.category_id = categories.id
        ")->fetchAll();
    }

    public function getByCategory($categoryId) {
        $stmt = $this->db->prepare("
            SELECT * FROM books WHERE category_id = ?
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO books (category_id, title, description, price, cover_image, stock)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE books SET category_id=?, title=?, description=?, price=?, cover_image=?, stock=?
            WHERE id=?
        ");
        return $stmt->execute([...$data, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id=?");
        return $stmt->execute([$id]);
    }
}
