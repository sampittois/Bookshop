<?php
class Book {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        return $this->db->query("
            SELECT books.*, categories.name AS category,
                   GROUP_CONCAT(authors.name SEPARATOR ', ') AS author_names
            FROM books
            LEFT JOIN categories ON books.category_id = categories.id
            LEFT JOIN book_authors ON books.id = book_authors.book_id
            LEFT JOIN authors ON book_authors.author_id = authors.id
            GROUP BY books.id
            ORDER BY books.title ASC
        ")->fetchAll();
    }

    public function getFiltered(?int $categoryId, string $searchTerm = '', string $sort = ''): array {
        $query = "
            SELECT books.*, categories.name AS category,
                   GROUP_CONCAT(authors.name SEPARATOR ', ') AS author_names
            FROM books
            LEFT JOIN categories ON books.category_id = categories.id
            LEFT JOIN book_authors ON books.id = book_authors.book_id
            LEFT JOIN authors ON book_authors.author_id = authors.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($categoryId)) {
            $query .= " AND books.category_id = :category";
            $params['category'] = $categoryId;
        }

        if ($searchTerm !== '') {
            $query .= " AND books.title LIKE :search";
            $params['search'] = "%$searchTerm%";
        }

        $query .= " GROUP BY books.id";

        switch ($sort) {
            case 'name_asc':
                $query .= " ORDER BY books.title ASC";
                break;
            case 'name_desc':
                $query .= " ORDER BY books.title DESC";
                break;
            case 'price_low':
                $query .= " ORDER BY books.price ASC";
                break;
            case 'price_high':
                $query .= " ORDER BY books.price DESC";
                break;
            default:
                $query .= " ORDER BY books.title ASC";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByCategory($categoryId) {
        $stmt = $this->db->prepare("
            SELECT * FROM books WHERE category_id = ?
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT books.*, categories.name AS category,
                   GROUP_CONCAT(authors.name SEPARATOR ', ') AS author_names
            FROM books
            LEFT JOIN categories ON books.category_id = categories.id
            LEFT JOIN book_authors ON books.id = book_authors.book_id
            LEFT JOIN authors ON book_authors.author_id = authors.id
            WHERE books.id = ?
            GROUP BY books.id
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByIds(array $ids): array {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT books.*, categories.name AS category,
                   GROUP_CONCAT(authors.name SEPARATOR ', ') AS author_names
            FROM books
            LEFT JOIN categories ON books.category_id = categories.id
            LEFT JOIN book_authors ON books.id = book_authors.book_id
            LEFT JOIN authors ON book_authors.author_id = authors.id
            WHERE books.id IN ($placeholders)
            GROUP BY books.id");
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public function getNewArrivals(int $limit = 6): array {
        $stmt = $this->db->prepare("SELECT books.*, categories.name AS category,
                   GROUP_CONCAT(authors.name SEPARATOR ', ') AS author_names
            FROM books
            LEFT JOIN categories ON books.category_id = categories.id
            LEFT JOIN book_authors ON books.id = book_authors.book_id
            LEFT JOIN authors ON book_authors.author_id = authors.id
            GROUP BY books.id
            ORDER BY books.id DESC
            LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO books (category_id, title, description, price, cover_image, stock, author)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE books SET category_id=?, title=?, description=?, price=?, cover_image=?, stock=?, author=?
            WHERE id=?");
        return $stmt->execute([...$data, $id]);
    }

    public function delete($id) {
        try {
            $this->db->beginTransaction();
            
            // Delete author associations first
            $stmt = $this->db->prepare("DELETE FROM book_authors WHERE book_id = ?");
            $stmt->execute([$id]);
            
            // Delete order items that reference this book
            $stmt = $this->db->prepare("DELETE FROM order_items WHERE book_id = ?");
            $stmt->execute([$id]);
            
            // Delete reviews for this book
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE book_id = ?");
            $stmt->execute([$id]);
            
            // Finally delete the book
            $stmt = $this->db->prepare("DELETE FROM books WHERE id=?");
            $stmt->execute([$id]);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
