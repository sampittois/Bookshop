<!DOCTYPE html>
<html>
<head>
    <title>Boekenwinkel – Home</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .filter { margin-bottom: 20px; }
        .book-grid { display: flex; gap: 20px; flex-wrap: wrap; }
        .book { width: 180px; border: 1px solid #ddd; padding: 10px; }
        .book img { width: 100%; height: 250px; object-fit: cover; }
        .book h3 { font-size: 18px; margin: 10px 0; }
    </style>
</head>
<body>

<h1>Nieuw binnen</h1>

<!-- Filter dropdown -->
<div class="filter">
    <form method="get" action="">
        <label for="category">Filter op categorie:</label>
        <select name="category" id="category" onchange="this.form.submit()">
            <option value="">Alle categorieën</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['id']) ?>"
                    <?= ($selectedCategory == $category['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<!-- Boek grid -->
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