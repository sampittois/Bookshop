<?php
require_once "../config.php";

$register_error = "";
$register_success = "";

if (!empty($_POST)) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    
    if ($password !== $password2) {
        $register_error = "Passwords do not match.";
    } else {
        $user = new User();
        if ($user->register($name, $email, $password)) {
            $register_success = "Registration successful! You can now log in.";
        } else {
            $register_error = "Email already exists or registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Bookshop</title>
  <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <div class="shopLogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 class="form__title">Create Account</h2>
                <nav class="nav--login">
                    <a href="login.php">Log in</a>
                    <a class="selected" href="register.php">Sign up</a>
                </nav>

                <?php if ($register_error): ?>
                    <div class="form__error">
                        <p><?= htmlspecialchars($register_error) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($register_success): ?>
                    <div class="form__success">
                        <p><?= htmlspecialchars($register_success) ?></p>
                    </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" required>
                </div>

                <div class="form__field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required>
                </div>

                <div class="form__field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="form__field">
                    <label for="password2">Confirm Password</label>
                    <input id="password2" type="password" name="password2" required>
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign up" class="btn btn--primary">
                </div>
            </form>
        </div>
    </div>
</body>
</html>