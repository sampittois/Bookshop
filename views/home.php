<?php
$db = new PDO("mysql:host=localhost;dbname=bookshop;charset=utf8", "root", "");

// AJAX endpoint for search suggestions
if (isset($_GET['ajax']) && $_GET['ajax'] === 'search') {
    $term = "%" . ($_GET['term'] ?? '') . "%";
    $stmt = $db->prepare("SELECT id, title FROM books WHERE title LIKE :term LIMIT 10");
    $stmt->execute(['term' => $term]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}


// AJAX endpoint for stock
if (isset($_GET['ajax']) && $_GET['ajax'] === 'stock') {
    $id = $_GET['id'] ?? 0;
    $stmt = $db->prepare("SELECT stock FROM books WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}


// Normal page load
$selectedCategory = $_GET['category'] ?? '';
$searchTerm = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';


$categories = $db->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);


$query = "SELECT * FROM books WHERE 1";
$params = [];


if (!empty($selectedCategory)) {
    $query .= " AND category_id = :category";
    $params['category'] = $selectedCategory;
}
if (!empty($searchTerm)) {
    $query .= " AND title LIKE :search";
    $params['search'] = "%$searchTerm%";
}


switch ($sort) {
    case 'name_asc':
        $query .= " ORDER BY title ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY title DESC";
        break;
    case 'price_low':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY price DESC";
        break;
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$allBooks = $stmt->fetchAll(PDO::FETCH_OBJ);
$books = array_slice($allBooks, 0, 4);

// Lees URL filters
$selectedCategory = $_GET['category'] ?? '';
$searchTerm       = $_GET['search'] ?? '';
$sort             = $_GET['sort'] ?? '';

// Haal categorieën op voor dropdown
$categories = $db->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Bouw basis query
$query = "SELECT * FROM books WHERE 1";
$params = [];

// Filter op categorie
if (!empty($selectedCategory)) {
    $query .= " AND category_id = :category";
    $params['category'] = $selectedCategory;
}

// Filter op zoekterm
if (!empty($searchTerm)) {
    $query .= " AND title LIKE :search";
    $params['search'] = "%$searchTerm%";
}

// Sorteer opties
switch ($sort) {
    case 'name_asc':
        $query .= " ORDER BY title ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY title DESC";
        break;
    case 'price_low':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY price DESC";
        break;
}

// Voer query uit
$stmt = $db->prepare($query);
$stmt->execute($params);
$allBooks = $stmt->fetchAll(PDO::FETCH_OBJ);

// NIEUW BINNEN boeken
$books = array_slice($allBooks, 0, 4); // of aparte query
?>

<!DOCTYPE html>
<html>

<head>
    <title>Boekenwinkel – Home</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        .suggestions {
            background: #fff;
            border: 1px solid #ccc;
            position: absolute;
            width: 200px;
            z-index: 99;
        }

        .suggestions div {
            padding: 5px;
            cursor: pointer;
        }

        .suggestions div:hover {
            background: #eee;
        }

        .stock-warning {
            color: red;
            font-weight: bold;
        }

        .filter {
            margin-bottom: 20px;
        }

        .book-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .book {
            width: 180px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .book img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .book h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .library-section {
            margin-top: 50px;
        }

        .filter-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-group form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>

    <h1>Nieuw binnen</h1>

    <!-- Nieuw binnen -->
    <div class="book-grid">
        <?php foreach ($books as $book): ?>
            <div class="book">
                <img src="<?= htmlspecialchars($book->cover_image) ?>" alt="<?= htmlspecialchars($book->title) ?>">
                <h3><?= htmlspecialchars($book->title) ?></h3>

                <h4>
                    <?php if (!empty($book->authors)): ?>
                        <?php foreach ($book->authors as $author): ?>
                            <?= htmlspecialchars($author->name) ?><br>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <em>Unknown author</em>
                    <?php endif; ?>
                </h4>

            </div>
        <?php endforeach; ?>
    </div>


    <!-- Bibliotheek -->
    <div class="library-section">
        <h2>Bibliotheek</h2>

        <!-- Filter categorie -->
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

        <div class="filter-group">
            <!-- Zoekformulier -->
            <!-- <form method="get" action="">
                <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
                <label for="search">Zoeken:</label>
                <input type="text" name="search" id="search"
                    value="<?= htmlspecialchars($searchTerm) ?>"
                    placeholder="Zoek op titel...">
                <button type="submit">Zoek</button>
            </form> -->

            <form method="get" action="">
                <label>Zoeken:</label>
                <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                <div id="suggestions" class="suggestions" style="display:none;"></div>
            </form>

            <!-- Sorteerformulier -->
            <form method="get" action="">
                <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
                <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">

                <label for="sort">Sorteren:</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="">-- Selecteer --</option>
                    <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Naam (A-Z)</option>
                    <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Naam (Z-A)</option>
                    <option value="price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Prijs (Laag-Hoog)</option>
                    <option value="price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Prijs (Hoog-Laag)</option>
                </select>
            </form>
        </div>

        <!-- Bib grid -->
        <!-- <div class="book-grid">
            <?php foreach ($allBooks as $book): ?>
                <div class="book">
                    <img src="<?= htmlspecialchars($book->cover_image) ?>" alt="<?= htmlspecialchars($book->title) ?>">
                    <h3><?= htmlspecialchars($book->title) ?></h3>
                    <h4>
                        <?php if (!empty($book->authors)): ?>
                            <?php foreach ($book->authors as $author): ?>
                                <?= htmlspecialchars($author->name) ?><br>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <em>Unknown author</em>
                        <?php endif; ?>
                    </h4>
                    <p>€<?= number_format($book->price, 2, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
        </div> -->
        <div class="book-grid">
            <?php foreach ($allBooks as $book): ?>
                <div class="book">
                    <img src="<?= htmlspecialchars($book->cover_image) ?>">
                    <h3><?= htmlspecialchars($book->title) ?></h3>
                    <?php if ($book->stock <= 5): ?>
                        <p class="stock-warning">Nog maar <?= $book->stock ?> op voorraad!</p>
                    <?php endif; ?>
                    <button onclick="checkStock(<?= $book->id ?>, this)">Check stock</button>
                    <p class="stock-result"></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <script>
        // AJAX live search
        const search = document.getElementById('search');
        const suggestions = document.getElementById('suggestions');


        search.addEventListener('input', () => {
            let term = search.value.trim();
            if (term.length < 1) {
                suggestions.style.display = 'none';
                return;
            }


            fetch(`?ajax=search&term=${term}`)
                .then(res => res.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.textContent = item.title;
                        div.onclick = () => {
                            search.value = item.title;
                            suggestions.style.display = 'none';
                        };
                        suggestions.appendChild(div);
                    });
                    suggestions.style.display = 'block';
                });
        });


        // AJAX check stock
        function checkStock(id, btn) {
            fetch(`?ajax=stock&id=${id}`)
                .then(res => res.json())
                .then(data => {
                    const p = btn.parentElement.querySelector('.stock-result');
                    p.textContent = `Voorraad: ${data.stock}`;
                });
        }
    </script>
</body>

</html>