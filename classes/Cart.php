<?php
class Cart {
    public static function add($bookId) {
        $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + 1;
    }

    public static function get() {
        return $_SESSION['cart'] ?? [];
    }

    public static function clear() {
        unset($_SESSION['cart']);
    }
}
