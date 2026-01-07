<?php
// Include database configuration
include('../config.php');

// Get the category filter from the request
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query to filter books by category
$sql = "SELECT * FROM books";
if ($category) {
    $sql .= " WHERE category = :category";
}

// Prepare and execute the statement
$stmt = $pdo->prepare($sql);
if ($category) {
    $stmt->bindParam(':category', $category);
}
$stmt->execute();

// Fetch the results
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($books);
?>