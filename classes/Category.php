<?php

class Category {
	private PDO $db;

	public function __construct() {
		$this->db = Database::connect();
	}

	/**
	 * Fetch all categories ordered by name.
	 */
	public function getAll(): array {
		$stmt = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC");
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Get a single category by id.
	 */
	public function getById(int $id): ?array {
		$stmt = $this->db->prepare("SELECT id, name FROM categories WHERE id = ? LIMIT 1");
		$stmt->execute([$id]);
		$category = $stmt->fetch(PDO::FETCH_ASSOC);
		return $category ?: null;
	}
}
