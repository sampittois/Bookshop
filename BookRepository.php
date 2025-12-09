<?php

class BookRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Haal de nieuwste boeken op, optioneel gefilterd op categorie
    public function getLatestBooks(int $limit = 3, ?int $categoryId = null): array {
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

    public function attachAuthors(array $books, AuthorRepository $authorRepo): array
{
    foreach ($books as $book) {
        $book->authors = $authorRepo->getAuthorsByBookId($book->getId());
    }
    return $books;
}

public function getFilteredBooks(?int $categoryId, ?string $search = null, ?string $sort = null): array {
    $sql = "SELECT * FROM books WHERE 1";
    $params = [];

    if ($categoryId) {
        $sql .= " AND category_id = :category";
        $params['category'] = $categoryId;
    }

    if ($search) {
        $sql .= " AND title LIKE :search";
        $params['search'] = "%$search%";
    }

    switch ($sort) {
        case 'name_asc':
            $sql .= " ORDER BY title ASC";
            break;
        case 'name_desc':
            $sql .= " ORDER BY title DESC";
            break;
        case 'price_low':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY price DESC";
            break;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

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