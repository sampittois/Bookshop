<?php

class BookRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Haal de nieuwste boeken op, optioneel gefilterd op categorie
    public function getLatestBooks(int $limit = 6, ?int $categoryId = null): array {
        if ($categoryId) {
            $sql = "SELECT * FROM books WHERE category_id = :cat ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cat', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM books ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
        }

        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book(
                $row['id'],
                $row['title'],
                (float) $row['price'],
                $row['cover_image'],
                $row['category_id']
            );
        }
        return $books;
    }
}