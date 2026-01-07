<?php
class Author {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Fetch all authors ordered by name.
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT id, name FROM authors ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single author by id.
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, name FROM authors WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        return $author ?: null;
    }

    /**
     * Get all books by a specific author via the book_authors junction table.
     */
    public function getBooksByAuthor(int $authorId): array {
        $stmt = $this->db->prepare("
            SELECT books.*, categories.name AS category
            FROM books
            INNER JOIN book_authors ON books.id = book_authors.book_id
            LEFT JOIN categories ON books.category_id = categories.id
            WHERE book_authors.author_id = ?
            ORDER BY books.title ASC
        ");
        $stmt->execute([$authorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new author.
     */
    public function create(string $name): int {
        $stmt = $this->db->prepare("INSERT INTO authors (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Link a book to an author via the book_authors junction table.
     */
    public function linkBookToAuthor(int $bookId, int $authorId): bool {
        $stmt = $this->db->prepare("INSERT IGNORE INTO book_authors (book_id, author_id) VALUES (?, ?)");
        return $stmt->execute([$bookId, $authorId]);
    }

    /**
     * Remove a book-author link.
     */
    public function unlinkBookFromAuthor(int $bookId, int $authorId): bool {
        $stmt = $this->db->prepare("DELETE FROM book_authors WHERE book_id = ? AND author_id = ?");
        return $stmt->execute([$bookId, $authorId]);
    }

    /**
     * Get all authors for a specific book.
     */
    public function getAuthorsByBook(int $bookId): array {
        $stmt = $this->db->prepare("
            SELECT authors.id, authors.name
            FROM authors
            INNER JOIN book_authors ON authors.id = book_authors.author_id
            WHERE book_authors.book_id = ?
            ORDER BY authors.name ASC
        ");
        $stmt->execute([$bookId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
