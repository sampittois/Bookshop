<?php

class BookRepository {

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Haal de 6 nieuwste boeken op
    public function getLatestBooks(int $limit = 6): array {
        $sql = "SELECT * FROM books ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $books = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book(
                $row['id'],
                $row['title'],
                (float) $row['price'],
                $row['cover_image']
            );
        }

        return $books;
    }
}
