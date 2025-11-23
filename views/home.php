<!DOCTYPE html>
<html>
<head>
    <title>Boekenwinkel – Home</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .book-grid { display: flex; gap: 20px; flex-wrap: wrap; }
        .book { width: 180px; border: 1px solid #ddd; padding: 10px; }
        .book img { width: 100%; height: 250px; object-fit: cover; }
        .book h3 { font-size: 18px; margin: 10px 0; }
    </style>
</head>
<body>

<h1>Nieuw binnen</h1>

<div class="book-grid">
    <?php foreach ($books as $book): ?>
        <div class="book">
            <img src="<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
            <h3><?= htmlspecialchars($book->getTitle()) ?></h3>
            <p>€<?= number_format($book->getPrice(), 2, ',', '.') ?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
