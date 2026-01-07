<?php
class Cart {
    public static function add($bookId) {
        $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + 1;
    }

    public static function get() {
        return $_SESSION['cart'] ?? [];
    }

    public static function setQuantity($bookId, $quantity) {
        $quantity = max(0, (int) $quantity);
        if ($quantity === 0) {
            self::remove($bookId);
            return;
        }
        $_SESSION['cart'][$bookId] = $quantity;
    }

    public static function remove($bookId) {
        unset($_SESSION['cart'][$bookId]);
        if (empty($_SESSION['cart'])) {
            self::clear();
        }
    }

    public static function clear() {
        unset($_SESSION['cart']);
    }
}
