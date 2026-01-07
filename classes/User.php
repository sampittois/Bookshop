<?php
class User {
    private PDO $db;
    private const BCRYPT_COST = 12; // Higher cost = stronger hashing but slower

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Create a new user with a hashed password (uses bcrypt with automatic salt)
     */
    public function createUser($name, $email, $password, $role = 'customer', $balance = 100.00) {
        // Check if user already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            // Delete existing user to recreate
            $stmt = $this->db->prepare("DELETE FROM users WHERE email = ?");
            $stmt->execute([$email]);
        }

        // Hash password with bcrypt with explicit cost and automatic salt
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);

        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password_hash, role, balance)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $email, $hash, $role, $balance]);
    }

    public function register($name, $email, $password) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) return false;

        // Hash password with bcrypt with explicit cost and automatic salt
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);

        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password_hash, role, balance)
            VALUES (?, ?, ?, 'customer', 100)
        ");
        return $stmt->execute([$name, $email, $hash]);
    }

    /**
     * Login user with email and password
     * password_verify automatically extracts and uses the salt stored in the hash
     */
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // password_verify extracts the salt from the hash and verifies
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            return true;
        }
        return false;
    }

    public function changePassword(int $userId, string $current, string $new): bool {
        $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $hash = $stmt->fetchColumn();
        if (!$hash || !password_verify($current, $hash)) {
            return false;
        }
        $newHash = password_hash($new, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);
        $update = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        return $update->execute([$newHash, $userId]);
    }

    public static function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        unset($_SESSION['user']);
        session_destroy();
    }

    public function deductBalance($userId, $amount) {
        $stmt = $this->db->prepare("
            UPDATE users SET balance = balance - ? WHERE id = ?
        ");
        return $stmt->execute([$amount, $userId]);
    }
}
