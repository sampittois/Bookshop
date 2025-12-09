<?php

class AuthorRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAuthorsByBookId($bookId)
    {
        $stmt = $this->pdo->prepare("
            SELECT a.*
            FROM authors a
            INNER JOIN book_authors ba ON ba.author_id = a.id
            WHERE ba.book_id = ?
        ");
        $stmt->execute([$bookId]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
